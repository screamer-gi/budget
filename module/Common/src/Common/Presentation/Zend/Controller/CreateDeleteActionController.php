<?php

namespace Common\Presentation\Zend\Controller;

use Common\Exceptions\ArgumentEmptyException;
use Common\Presentation\Zend\Form\Parameters\CreateActionFactoryInterface;
use Common\Service\CreateDeleteServiceInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;

class CreateDeleteActionController extends AbstractActionController
{
    private $params;
    private $resourceName;
    private $paramsFactory;
    private $service;
    private $actionMaker;

    public function __construct
        ( $resourceName
        , CreateActionFactoryInterface $paramsFactory
        , CreateDeleteServiceInterface $service
        , ActionMaker                  $actionMaker )
    {
        if (empty($resourceName)) throw new ArgumentEmptyException('$resourceName');

        $this->params         = $this->params();
        $this->resourceName   = $resourceName;
        $this->paramsFactory  = $paramsFactory;
        $this->service        = $service;
        $this->actionMaker    = $actionMaker;
    }

    /**
     * Создание
     * @return JsonModel
     */
    public function createAction()
    {
        return $this->actionMaker->defaultCreateAction($this->params, $this->paramsFactory, $this->service);
    }

    /**
     * Удаление
     * @return JsonModel
     */
    public function deleteAction()
    {
        return $this->actionMaker->defaultDeleteAction($this->params, $this->service);
    }
} 