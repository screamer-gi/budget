<?php

namespace Application\Infrastructure\Zend;


use Common\Service\Translator\AbstractTranslator;

class Translator //extends AbstractTranslator
{

    public function translate($key, array $params = [])
    {
        foreach ($params as $name => $value) {
            $key = str_replace(':' . $name . ':', $value, $key);
        }

        return $key;
    }
}