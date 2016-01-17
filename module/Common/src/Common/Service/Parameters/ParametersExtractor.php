<?php

namespace Common\Service\Parameters;

use Common\Exceptions\ParameterNotFound;

class ParametersExtractor
{
    private $parameters = [];

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function get($parameterName)
    {
        if (! ($parameter = $this->find($parameterName))) {
            //throw new ParameterNotFound($parameterName);
        }
        return $parameter;
    }

    public function find($parameterName, $default = null)
    {
        if (! isset($this->parameters[$parameterName])) {
            return $default;
        }

        return $this->parameters[$parameterName];
    }
} 