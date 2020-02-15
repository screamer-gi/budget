<?php
namespace Expense;

use Expense\Presentation\Controller\ExpenseController;
use Laminas\Di\Container\AutowireFactory;

return [
    'controllers' => [
        'factories' => [
            ExpenseController::class => AutowireFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'expense' => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/expense[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => ExpenseController::class,
                        'action'     => 'index',
                    ],
                ],
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
        'template_path_stack' => [
            'expense' => __DIR__ . '/../view',
        ],
    ]
];
