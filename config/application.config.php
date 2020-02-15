<?php
return [
    'modules' => [
        'Laminas\Navigation',
        'Laminas\I18n',
        'Laminas\Cache',
        'Laminas\Di',
        'Laminas\Form',
        'Laminas\Router',
        'DoctrineModule',
        'DoctrineORMModule',
        'Application',
        'Expense',
        'AnnualReport',
    ],

    'module_listener_options' => [
        'config_glob_paths'    => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths' => [
            './module',
            './vendor',
        ],
    ],
];
