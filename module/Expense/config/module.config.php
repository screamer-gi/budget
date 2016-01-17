<?php
namespace Expense;

use Expense\Presentation\Controller\ExpenseController;
use Zend\Mvc\Controller\ControllerManager;

return [
    'zendDiCompiler' => [
        'scanDirectories' => [
            __DIR__ . '/../src/' . __NAMESPACE__,
        ],
    ],
    'di' => [
        'instance' => [
            'preference' => [
                //\Doctrine\Common\Persistence\ObjectManager::class => \Doctrine\ORM\EntityManager::class
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            'Expense\Controller\Expense' => function(ControllerManager $cm) {
                return $cm->getServiceLocator()->/*get('diService')->*/get(ExpenseController::class);
            },
            /*'Expense\Controller\ExpenseCategory' => function($cm) {
                return (new Factory())->get('Expense\Controller\ExpenseCategoryController', $cm);
            },*/
        ]
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
                        'controller' => 'Expense\Controller\Expense',
                        'action'     => 'index',
                    ],
                ],
            ],
            /*'expense-category' => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/expense-category[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => 'Expense\Controller\ExpenseCategory',
                        'action'     => 'index',
                    ],
                ],
            ],*/
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