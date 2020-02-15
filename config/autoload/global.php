<?php

use Functional as F;

return [
    'invokables' => [
        'Doctrine\ORM\Mapping\UnderscoreNamingStrategy' => 'Doctrine\ORM\Mapping\UnderscoreNamingStrategy',
        'Doctrine\ORM\Mapping\AnsiQuoteStrategy' => 'Doctrine\ORM\Mapping\AnsiQuoteStrategy',
    ],

    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      =>
                    [ 'host'    => 'localhost'
                    , 'port'    => '3306'
                    , 'dbname'  => 'budget'
                    , 'charset' => 'utf8'
                    ]
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'generate_proxies' => false,
//                'quote_strategy' => Doctrine\ORM\Mapping\DefaultQuoteStrategy::class,
//                'naming_strategy' => 'Doctrine\ORM\Mapping\UnderscoreNamingStrategy',
            ],
        ],
        'driver'        => call_user_func(function () {
            $driverName = 'LibDriver';
            $libModules = [];

            $paths      = F\map($libModules, function ($module) { return "$module/Persistence/Entity"; });
            $drivers    =
                F\reduce_left(
                    $libModules,
                    function ($modulePath, $_1, $_2, $acc) use ($driverName) {
                        $moduleName = basename($modulePath);
                        $acc["Lib\\{$moduleName}\\Persistence\\Entity"] = $driverName;
                        return $acc;
                    },
                    []
                );

            return [
                $driverName =>
                    [ 'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver'
                        , 'cache' => 'array'
                        , 'paths' => $paths
                    ],
                'orm_default' => ['drivers' => $drivers]
            ];
        })
    ],
];
