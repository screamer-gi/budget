<?php
namespace Common\Presentation\Zend\Controller;

use Zend\Mvc\Controller\ControllerManager;

class Factory
{
    public function get($controllerClass, ControllerManager $manager)
    {
        $di = $manager->getServiceLocator()->get('diService');
        return $di->get($controllerClass);
    }
}
