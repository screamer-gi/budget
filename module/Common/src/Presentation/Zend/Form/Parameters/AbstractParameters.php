<?php
namespace Common\Presentation\Zend\Form\Parameters;

use Common\Service\CommonServiceInterface;
use Common\Service\Parameters\ParametersInterface;

abstract class AbstractParameters implements ParametersInterface
{
    protected $_inputFilter = null;

    public function __construct(array $data)
    {
        $this->populate($data);
    }

    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    public function __get($name)
    {
        if (! property_exists($this, $name)) {
            throw new PropertyNotExistsException($name);
        }
        return $this->$name;
    }

    public function getArrayCopy()
    {
        $arrayCopy  = [];
        $properties = array_filter(array_keys(get_object_vars($this)), function ($property) {
            return $property[0] != '_';
        });

        foreach ($properties as $propertyName) {
            $arrayCopy[$propertyName] = $this->__get($propertyName);
        }

        return $arrayCopy;
    }

    public function populate(array $data)
    {
        foreach ($data as $k => $v) {
            $this->__set($k, $v);
        }
    }

    public function isValid()
    {
        $inputFilter = $this->getInputFilter()->setData($this->getArrayCopy());
        return $inputFilter->isValid();
    }

    public function getExtErrors()
    {
        $errors = $this->getInputFilter()->getMessages();
        $result = [];
        array_walk($errors, function ($errors, $param) use (&$result) {
            $result[] = [
                'id'  => $param,
                'msg' => array_reduce($errors, function ($prevErr, $err) { return $prevErr . $err . PHP_EOL; }, '')
            ];
        });

        return $result;
    }

    public abstract function getInputFilter();
}