<?php
namespace Common\Presentation\Zend\Form\Parameters;

interface CommonFactoryInterface extends CreateActionFactoryInterface
{
    /**
     * Параметры списка сущностей
     * @param array $data
     * @return AbstractParameters
     */
    public function getListParameters(array $data);

    /**
     * Параметры редактирования сущности
     * @param array $data
     * @return AbstractParameters
     */
    public function getEditParameters(array $data);

    /**
     * Обновление параметров сущности
     * @param array $data
     * @return AbstractParameters
     */
    public function getUpdateParameters(array $data);
}