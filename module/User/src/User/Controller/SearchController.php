<?php
namespace User\Controller;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Authentication\Adapter\ObjectRepository;
use FileStore\Service\FileService;
use Lib\Auth\Service\AclInterface;
use Lib\Auth\Service\AuthInterface;
use User\Repository\SearchRepository;
use User\Service\UserService;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Result as Result;
use Laminas\Http\Headers;
use Laminas\Http\Response\Stream;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class SearchController extends AbstractActionController
{
    /**
     * @var SearchRepository
     */
    private $searchRepository;

    /**
     * @var AclInterface
     */
    private $acl;

    public function __construct(SearchRepository $searchRepository, AclInterface $acl)
    {
        $this->searchRepository = $searchRepository;
        $this->acl              = $acl;
    }

    public function searchAction()
    {
        if (!$this->acl->isAllowed('search', 'list')) {
            return $this->getResponse()->setStatusCode(403);
        }

        return new JsonModel([
            'success' => true,
            'list'    => $this->searchRepository->search($this->params()->fromQuery('search'))
        ]);
    }
}
