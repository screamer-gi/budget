<?php
namespace Common\Service\Parameters;

interface ParametersInterface
{
    public function __set($name, $value);

    /**
     * Получение параметра
     * Если параметр не найден должно возникать исключение
     * Lib\Application\Form\Parameters\PropertyNotExistsException
     * @param $name
     * @return mixed
     */
    public function __get($name);
    public function getArrayCopy();
    public function populate(array $data);
}