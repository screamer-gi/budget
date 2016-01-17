<?php

namespace Application;

use Application\DI\DIConfigurator;
use Zend\Http\Response;
use Zend\Mvc\ModuleRouteListener;
use Zend\ServiceManager\ServiceManager;

class Module
{
    public function onBootstrap($e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        /** @var $serviceManager ServiceManager */
        $serviceManager      = $e->getApplication()->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        //$di = $this->configureDI($serviceManager);

        // Flush-per-request
        $eventManager->attach('dispatch', function ($event) use ($serviceManager) {
            /** @var $em \Doctrine\ORM\EntityManager */
            $em  = $serviceManager->get('Doctrine\ORM\EntityManager');
            if ($em->isOpen()) {
//                $em->flush();
//                echo ('flush');
            }
        }, -10);

        // auth
        if (false)
        $eventManager->attach('dispatch', function ($event) use ($di) {
            $authService = $di->get('Application\Service\Zend\AuthService');
            /**
             * if user not authenticated deny request
             * bypass routes "home" and "auth"
             */
            if (!in_array($event->getRouteMatch()->getMatchedRouteName(), ['home', 'user', 'config', 'notify-idle', 'poll', 'notify']) && !$authService->hasIdentity() ) {
                $response = $event->getResponse();
                $response->setStatusCode(401); // 401 Unauthorized
                // shortcircuit by returning response
                return $response;
            }
        }, 100);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [ __DIR__ . '/autoload_classmap.php' ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__  => __DIR__ . '/src/' . __NAMESPACE__,
                ]
            ]
        ];
    }

    private function configureDI(ServiceManager $serviceManager)
    {
        $diConfigurator = new DIConfigurator
            ( __DIR__ . '/../../data/di'
            , __DIR__ . '/..'
            , $serviceManager
            );

        $diConfigurator->configure();
        $serviceManager->setService('diService', $diConfigurator->getServiceLocator());
        return $diConfigurator->getServiceLocator();
    }
}
