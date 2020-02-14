<?php

use Application\Controller\IndexController;
use Application\DummyTranslator;
use Application\Infrastructure\Doctrine\DoctrineObjectHydratorFactory;
use Doctrine\Laminas\Hydrator\DoctrineObject;
use Laminas\Router\Http\Literal;
use Laminas\ServiceManager\Factory\InvokableFactory;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => Literal::class,
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            )
                        ),
                    ),
                ),
            ),
        ),
    ),

    'navigation' => [
        'default' => [
            [
                'label' => 'Бюджет',
                'route' => 'home',
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
//            'translator' => 'Laminas\I18n\Translator\TranslatorServiceFactory',
            DoctrineObject::class => DoctrineObjectHydratorFactory::class,
        ],
        'aliases' => [
            Doctrine\Common\Persistence\ObjectManager::class => Doctrine\ORM\EntityManager::class,
        ],
        'shared' => [
            DoctrineObject::class => false,
        ],
    ],
//    'translator' => array(
//        'locale' => 'en_US',
//        'translation_patterns' => array(
//            array(
//                'type'     => 'gettext',
//                'base_dir' => __DIR__ . '/../language',
//                'pattern'  => '%s.mo',
//            ),
//        ),
//    ),
    'controllers' => array(
        'factories' => [
            IndexController::class => InvokableFactory::class,
        ],
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'view_helpers' => [
        'aliases' => [
            'translate' => DummyTranslator::class,
        ],
        'factories' => [
            DummyTranslator::class => InvokableFactory::class,
        ],
    ],
);
