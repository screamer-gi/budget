<?php
namespace Common\Presentation\Zend\Controller;

use Application\Filters\FiltersSetInterface;
use Functional as F;
use Common\Presentation\Zend\Form\Parameters\AbstractParameters;
use Common\Presentation\Zend\Form\Parameters\CommonFactoryInterface;
use Common\Presentation\Zend\Form\Parameters\CreateActionFactoryInterface;
use Common\Presentation\Zend\Form\Parameters\PublisherFactoryInterface;
use Common\Service\CommonServiceInterface;
use Common\Service\CreateDeleteServiceInterface;
use Common\Service\PublisherServiceInterface;
use Laminas\View\Model\JsonModel;

class ActionMaker
{
    public function defaultListAction
        ( $resourceName
        , CommonFactoryInterface $paramsFactory
        , CommonServiceInterface $service
        , FiltersSetInterface    $listFiltersSet = null
        )
    {
        if (method_exists($service, 'canList') && $service->canList() === false) {
            return $this->getResponse()->setStatusCode(403);
        }

        if ($listFiltersSet) $listFiltersSet->enablePaging();
        return new JsonModel([
            'list'  => F\map(
                $service->getList($listFiltersSet),
                function ($resource) use ($paramsFactory, $resourceName) {
                    return $paramsFactory->getListParameters([$resourceName => $resource])->$resourceName;
                }
            ),
            'total' => count($service->getList($listFiltersSet ? $listFiltersSet->disablePaging() : null))
        ]);
    }

    public function defaultEditAction
        ( $params
        , CommonFactoryInterface $paramsFactory
        , CommonServiceInterface $service
        )
    {
        $resourceId = $params->fromPost('id');
        $parameters = $paramsFactory->getEditParameters($params->fromPost());
        $success    = true;

        if (method_exists($service, 'canEdit') && $service->canEdit($resourceId) === false) {
            return $this->getResponse()->setStatusCode(403);
        }

        if ($parameters->isValid()) {
            $service->edit($resourceId, $parameters);
        } else {
            $success = false;
        }

        return new JsonModel([
            'success' => $success,
            'errors'  => $parameters->getExtErrors()
        ]);
    }

    public function defaultCreateAction
        ( $params
        , CreateActionFactoryInterface $paramsFactory
        , CreateDeleteServiceInterface $service
        )
    {
        if (method_exists($service, 'canCreate') && $service->canCreate() === false) {
            return $this->getResponse()->setStatusCode(403);
        }

        $parameters = $paramsFactory->getCreateParameters($params->fromPost());
        $success    = true;

        if ($parameters->isValid()) {
            $service->create($parameters);
        } else {
            $success = false;
        }

        return new JsonModel([
            'success' => $success,
            'errors'  => $parameters->getExtErrors()
        ]);
    }

    public function defaultDeleteAction($params, CreateDeleteServiceInterface $service)
    {
        if (method_exists($service, 'canDelete') && $service->canDelete($params->fromPost('id')) === false) {
            return $this->getResponse()->setStatusCode(403);
        }

        $service->delete($params->fromPost('id'));

        return new JsonModel([ 'success' => true ]);
    }

    public function defaultUpdateAction
        ( $params
        , $resourceName
        , CommonFactoryInterface $paramsFactory
        , CommonServiceInterface $service
        )
    {
        $resource = $service->find($params()->fromQuery('id'));

        if (method_exists($service, 'canEdit') && $service->canEdit($resource) === false) {
            return $this->getResponse()->setStatusCode(403);
        }

        return new JsonModel([
            'success'  => $resource ? true : false,
            'resource' => $resource ? $paramsFactory->getUpdateParameters([$resourceName => $resource])->$resourceName
                                    : null,
            'deleted'  => $resource ? false : true
        ]);
    }

    public function defaultPublishedAction
        ( $resourceName
        , PublisherFactoryInterface $paramsFactory
        , PublisherServiceInterface $service
        )
    {
        if (method_exists($service, 'canList') && $service->canList() === false) {
            return $this->getResponse()->setStatusCode(403);
        }

        return new JsonModel([
            'published' => F\map(
                $service->getPublished(),
                function ($element) use ($paramsFactory, $resourceName) {
                    return $paramsFactory->getPublishedParameters([$resourceName => $element])->$resourceName;
                }
            )
        ]);
    }

    public function defaultValidateAction($params, CommonFactoryInterface $paramsFactory)
    {
        $parameters = $paramsFactory->getCreateParameters($params->fromPost());

        return new JsonModel([
            'success' => $parameters->isValid(),
            'errors'  => $parameters->getExtErrors()
        ]);
    }

    public function defaultChangeAction(AbstractParameters $params, $onValid)
    {
        $success = true;

        if ($params->isValid()) {
            $onValid();
        } else {
            $success = false;
        }

        return new JsonModel(
            [ 'success' => $success
            , 'errors'  => $params->getExtErrors()
            ]
        );
    }
}