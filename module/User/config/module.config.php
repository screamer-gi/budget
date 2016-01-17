<?php
use Application\Controller\Factory;

return array(
    'controllers' => array(
        'factories' => array(
            'User\Controller\User' => function($cm) { return (new Factory())->get('User\Controller\UserController', $cm);} ,
            'Search' => function($cm) { return (new Factory())->get('User\Controller\SearchController', $cm);}
        )
    ),

    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action'     => 'index',
                    ),
                ),
            ),
            'search' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/search',
                    'defaults' => array(
                        'controller' => 'Search',
                        'action'     => 'search',
                    ),
                ),
            )
        ),
    ),

    'view_manager' => [
        'template_path_stack' => [
            'user' => __DIR__ . '/../view',
        ],
    ]
);