<?php
error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

class Bootstrap
{
    public static function init()
    {
        $composerAutoLoader = include __DIR__ . '/../vendor/autoload.php';
        spl_autoload_register([$composerAutoLoader, 'loadClass']);

        $modulesNamespaces = array_reduce(
            glob(__DIR__ . '/../module/*', GLOB_ONLYDIR),
            function ($acc, $dir) {
                $moduleName = basename($dir);
                $acc[$moduleName] = __DIR__ . "/../module/{$moduleName}/src/{$moduleName}";
                return $acc;
            },
            []
        );
        $zendAutoLoader = new Laminas\Loader\StandardAutoloader([ 'namespaces' => $modulesNamespaces ]);
        spl_autoload_register([$zendAutoLoader, 'autoload']);
    }
}

Bootstrap::init();
