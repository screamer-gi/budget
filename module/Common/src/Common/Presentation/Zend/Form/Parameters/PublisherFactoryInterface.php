<?php
namespace Common\Presentation\Zend\Form\Parameters;


interface PublisherFactoryInterface 
{
    /**
     * Параметры опубликованных сущностей
     * @param array $data
     * @return AbstractParameters
     */
    public function getPublishedParameters(array $data);
}