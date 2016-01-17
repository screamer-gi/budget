<?php
/**
 * Образец локальной конфигурации.
 * Скопируйте local.dist.php в local.php и поправьте в нем настройки. Файл local.php игнорируется svn
 */

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => [
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'budget',
                ]
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'generate_proxies'  => false
            ]
        ]
    ],
];
