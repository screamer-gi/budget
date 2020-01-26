<?php
return array(
    'modules' => array(
        'Laminas\Router',
//        'DoctrineModule',
//        'DoctrineORMModule',
        'Application',
        //'Administration',
        //'User',
        //'Auth',
//        'Common',
        'Expense',
    )/* + ['ZendDiCompiler']*/,

    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
