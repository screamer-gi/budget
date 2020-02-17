<?php

use AnnualReport\Controller;
use Laminas\Di\Container\AutowireFactory;

return [
    'controllers' => [
        'factories' => [
            Controller::class => AutowireFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'annual-report' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/annual-report',
                    'defaults' => [
                        'controller' => Controller::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            [
                'label' => 'Годовой отчет',
                'route' => 'annual-report',
            ],
        ],
    ],

    'view_manager' => [
        'prefix_template_path_stack' => [
            'annual-report/controller' => __DIR__ . '/../view',
        ],
    ],
];
