<?php
namespace User\Controller;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use FileStore\Service\FileService;
use Lib\Auth\Service\AuthInterface;
use User\Service\UserService;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result as Result;
use Zend\Http\Headers;
use Zend\Http\Response\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class UserController extends AbstractActionController
{
    private $authService;
    private $userService;
    private $fileService;
    private $entityManager;

    public function __construct
        ( AuthInterface $authService
        , UserService   $userService
        , FileService   $fileService
        , EntityManager $entityManager )
    {
        $this->authService   = $authService;
        $this->userService   = $userService;
        $this->fileService   = $fileService;
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $auth = $this->authService;

        $identity = null;
        if ($auth->hasIdentity()) {
            $identity = $auth->getIdentity();
        }

        return array(
            'identity' => $identity,
        );
    }

    public function loginAction()
    {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $response = $this->getResponse();
            $response->setStatusCode(405); // 405 Method Not Allowed
            return $response;
        }

        // get post data
        $post = $request->getPost();

        $doctrineAuthAdapter = new ObjectRepository([
            'objectManager' => $this->entityManager,
            'objectRepository' => $this->entityManager->getRepository('Staff\Entity\Employee'),
            'identityClass' => 'Staff\Entity\Employee',
            'identityProperty' => 'login',
            'credentialProperty' => 'password',
            'credentialCallable' => [$this->authService, 'hashPassword']
        ]);

        $doctrineAuthAdapter->setIdentity($post->get('username'));
        $doctrineAuthAdapter->setCredential($post->get('password'));

        // create auth service and set adapter
        // auth services provides storage after authenticate
        $authService = $this->authService;
        $authService->setAdapter($doctrineAuthAdapter);

        // authenticate
        $result = $authService->authenticate();

        // check if authentication was successful
        // if authentication was successful, user information is stored automatically by adapter
        if ($result->isValid()) {
            if (!$result->getIdentity()->enabled) {
                // неактивный пользователь
                return new JsonModel([
                    'success' => false,
                    'errors'  => ['Неактивный пользователь']
                ]);
            }
            $authService->getStorage()->write($result->getIdentity()->id);

            return new JsonModel([
                'success' => true,
                'errors'  => []
            ]);
        }

        /**
         * Если по каким бы то ни было причинам $result->isValid()
         * вернул false, то возвращаем результат работы адаптера.
         * Помним, что результат может быть Result::SUCCESS
         */
        $_success = true;
        switch ($result->getCode()) {
                case Result::FAILURE_CREDENTIAL_INVALID:
                case Result::FAILURE_IDENTITY_NOT_FOUND:
                default:
                    $_success = false;
                    break;
                case Result::SUCCESS:
                    /** do stuff for successful authentication * */
                    break;
        }

        return new JsonModel([
            'success' => $_success,
            'errors'  => [ $result->getCode() ]
        ]);
    }

    public function logoutAction()
    {
        $auth = new AuthenticationService();
        $auth->clearIdentity();

        return $this->redirect()->toRoute('home');
    }

    /**
     * Текущий пользователь
     * @return JsonModel
     */
    public function currentAction()
    {   /** @class Staff\Entity\Employee */
        $user = $this->authService->getEntity();

        return new JsonModel([
              'id'         => $user->id
            , 'fullName'   => $user->fullname
            , 'department' => $user->department->title
        ]);
    }

    public function avatarAction()
    {
        $user = $this->authService->getEntity();
        $file = $user->photo;
        $path = $file ? $file->fullName : '/../../public/images/no_image.gif';
        $ext  = $file ? $file->extension : 'gif';


        $response = new Stream();
        $response->setStream(fopen($this->fileService->getStorePath() . $path, 'r'));
        $response->setStatusCode(200);

        $headers = new Headers();
        $headers->addHeaderLine('Content-Type' , 'image/' . $ext);
        $headers->addHeaderLine('Expires'      , 'Thu, 15 Apr 2014 20:00:00 GMT');

        $response->setHeaders($headers);
        return $response;
    }

    public function restorePasswordAction()
    {
        $phone      = $this->params()->fromPost('phone');
        $answer     = $this->params()->fromPost('answer', false);
        $parameters = ['success' => false];

        if ($answer) {
            $parameters['success'] = $this->userService->restorePassword($phone, $answer);
        } else if ($parameters['question'] = $this->userService->findSecretQuestionByPhone($phone)) {
            $parameters['success'] = true;
        }

        return new JsonModel($parameters);
    }
}
