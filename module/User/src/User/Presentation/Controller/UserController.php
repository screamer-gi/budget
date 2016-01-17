<?php

namespace Lib\User\Presentation\Controller;

use Lib\Auth\Service\AuthInterface;
use Lib\FileStore\Service\FileService;
use Lib\User\Presentation\Form\LoginForm;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result as Result;
use Zend\Http\Headers;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class UserController extends AbstractActionController
{
    private $authService;
    private $fileService;

    public function __construct(
        AuthInterface $authService
      , FileService   $fileService
    ) {
        $this->authService = $authService;
        $this->fileService = $fileService;
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
        $form = new LoginForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            // get post data
            $post = $request->getPost();

            // get the db adapter
            $sm = $this->getServiceLocator();
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');

            // create auth adapter
            $authAdapter = new AuthAdapter($dbAdapter);

            // configure auth adapter
            $authAdapter->setTableName('employee')
                ->setIdentityColumn('login')
                ->setCredentialColumn('password');

            // pass authentication information to auth adapter
            $authAdapter->setIdentity($post->get('username'))
                ->setCredential(/*md5*/($post->get('password')));

            // create auth service and set adapter
            // auth services provides storage after authenticate
            $authService = $this->authService;
            $authService->setAdapter($authAdapter);

            // authenticate
            $result = $authService->authenticate();

            // check if authentication was successful
            // if authentication was successful, user information is stored automatically by adapter
            if ($result->isValid()) {
                $resultRow = $authService->getAdapter()->getResultRowObject();
                $authService->getStorage()->write($resultRow->id);
                // redirect to user index page

                return new JsonModel([
                    'success' => true,
                    'errors'  => []
                ]);
            } else {
                switch ($result->getCode()) {
                    case Result::FAILURE_IDENTITY_NOT_FOUND:
                        /** do stuff for nonexistent identity * */
                        break;

                    case Result::FAILURE_CREDENTIAL_INVALID:
                        /** do stuff for invalid credential * */
                        break;

                    case Result::SUCCESS:
                        /** do stuff for successful authentication * */
                        break;

                    default:
                        /** do stuff for other failure * */
                        break;
                }
                return new JsonModel([
                    'success' => false,
                    'errors'  => []
                ]);
            }
        }

        return array('form' => $form);
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
    {
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


        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($this->fileService->getStorePath() . $path, 'r'));
        $response->setStatusCode(200);

        $headers = new Headers();
        $headers->addHeaderLine('Content-Type' , 'image/' . $ext);
        $headers->addHeaderLine('Expires'      , 'Thu, 15 Apr 2014 20:00:00 GMT');

        $response->setHeaders($headers);
        return $response;
    }
}