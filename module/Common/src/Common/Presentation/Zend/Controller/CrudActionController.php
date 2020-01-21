<?php
namespace Common\Presentation\Zend\Controller;

use Application\Filters\Set\CommonFactoryInterface                  as CommonFiltersFactory;
use Common\Presentation\Zend\Form\Parameters\CommonFactoryInterface as CommonParamsFactory;
use Common\Exceptions\ArgumentEmptyException;
use Common\Service\CommonServiceInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class CrudActionController extends AbstractActionController
{
    private $params;
    private $resourceName;
    private $paramsFactory;
    private $filtersFactory;
    private $service;
    private $actionMaker;
    private $securityRules;

    public function __construct
        ( $resourceName
        , CommonParamsFactory    $paramsFactory
        , CommonFiltersFactory   $filtersFactory = null
        , CommonServiceInterface $service
        , ActionMaker            $actionMaker
        , array                  $securityRules = []
        )
    {
        if (empty($resourceName)) throw new ArgumentEmptyException ('$resourceName');

        $this->params         = $this->params();
        $this->resourceName   = $resourceName;
        $this->paramsFactory  = $paramsFactory;
        $this->filtersFactory = $filtersFactory;
        $this->service        = $service;
        $this->actionMaker    = $actionMaker;
        $this->securityRules  = $securityRules;
    }

    /**
     * Страница ресурса
     */
    public function indexAction() {}

    /**
     * Список
     * @return JsonModel
     */
    public function listAction()
    {
        if (array_key_exists('list', $this->securityRules) && !$this->securityRules['list']) {
            return $this->getResponse()->setStatusCode(403);
        }

        return $this->actionMaker->defaultListAction
            ( $this->resourceName
            , $this->paramsFactory
            , $this->service
            , $this->filtersFactory ? $this->filtersFactory->getListSet($this->params->fromQuery()) : null
            );
    }

    /**
     * Создание
     * @return JsonModel
     */
    public function createAction()
    {
        if (array_key_exists('create', $this->securityRules) && !$this->securityRules['create']) {
            return $this->getResponse()->setStatusCode(403);
        }
        return $this->actionMaker->defaultCreateAction($this->params, $this->paramsFactory, $this->service);
    }

    /**
     * Редактирование
     * @return JsonModel
     */
    public function editAction()
    {
        if (array_key_exists('edit', $this->securityRules) && !$this->securityRules['edit']) {
            return $this->getResponse()->setStatusCode(403);
        }
        return $this->actionMaker->defaultEditAction($this->params, $this->paramsFactory, $this->service);
    }

    /**
     * Удаление
     * @return JsonModel
     */
    public function deleteAction()
    {
        if (array_key_exists('delete', $this->securityRules) && !$this->securityRules['delete']) {
            return $this->getResponse()->setStatusCode(403);
        }
        return $this->actionMaker->defaultDeleteAction($this->params, $this->service);
    }

    /**
     * Обновление записи
     * @return JsonModel
     */
    public function updateAction()
    {
        $securityKey = 'update';
        if (array_key_exists($securityKey, $this->securityRules) && !is_callable($this->securityRules[$securityKey]) && !$this->securityRules[$securityKey]) {
            return $this->getResponse()->setStatusCode(403);
        }
        $view = $this->actionMaker->defaultUpdateAction
            ( $this->params
            , $this->resourceName
            , $this->paramsFactory
            , $this->service
            );
        if (array_key_exists($securityKey, $this->securityRules) &&
            is_callable($this->securityRules[$securityKey]) &&
            call_user_func($this->securityRules[$securityKey], $view->resource) == false) {

            return $this->getResponse()->setStatusCode(403);
        }

        return $view;
    }

    /**
     * Просмотр записи
     * @return JsonModel
     */
    public function viewAction()
    {
        $securityKey = 'view';
        if (array_key_exists($securityKey, $this->securityRules) && !is_callable($this->securityRules[$securityKey]) && !$this->securityRules[$securityKey]) {
            return $this->getResponse()->setStatusCode(403);
        }
        $view = $this->actionMaker->defaultUpdateAction
            ( $this->params
            , $this->resourceName
            , $this->paramsFactory
            , $this->service
            );
        if (array_key_exists($securityKey, $this->securityRules) &&
            is_callable($this->securityRules[$securityKey]) &&
            call_user_func($this->securityRules[$securityKey], $view->resource) == false) {

            return $this->getResponse()->setStatusCode(403);
        }

        return $view;
    }
}