<?php

namespace Application;

class DummyTranslator
{
    public function __invoke($string)
    {
        return $string;
    }
}
