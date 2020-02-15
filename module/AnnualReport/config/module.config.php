<?php

use Expense\Presentation\Controller\ExpenseController;
use Laminas\Di\Container\AutowireFactory;

return [
    'controllers' => [
        'factories' => [
//            ExpenseController::class => AutowireFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'annual-report' => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/annual-report[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
//                        'controller' => ExpenseController::class,
//                        'action'     => 'index',
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

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Persistence/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Persistence\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],

    'view_manager' => [
        'prefix_template_path_stack' => [
            'expense/presentation/expense' => __DIR__ . '/../view/expense/expense',
        ],
    ],
];
