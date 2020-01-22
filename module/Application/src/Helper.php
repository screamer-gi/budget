<?php

namespace Application;

use Application\Controller\Factory;

class Helper
{
    public static function makeControllersFactory(array $controllerNames)
    {
        $controllers = [];

        foreach ($controllerNames as $controllerName) {
            $controllers[$controllerName] = function($cm) use ($controllerName) {
                return (new Factory())->get($controllerName . 'Controller', $cm);
            };
        }

        return $controllers;
    }
}