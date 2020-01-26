<?php
namespace Common\Presentation\Zend\Form\Parameters;

interface CreateActionFactoryInterface
{
    /**
     * Параметры создания сущности
     * @param array $data
     * @return AbstractParameters
     */
    public function getCreateParameters(array $data);
}