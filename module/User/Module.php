<?php
namespace User;

use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable as DbTableAuthAdapter;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Create auth service
     */
    public function getServiceConfig()
    {
        return array(
            'factories'=>array(
                'Lib\Auth\Persistence\Storage\Storage' => function($sm){
                    return new \Lib\Auth\Persistence\Storage\Storage();
                },

                'AuthService' => function($sm) {
                    //My assumption, you've alredy set dbAdapter
                    //and has users table with columns : user_name and pass_word
                    //that password hashed with md5
                    /** @todo auth MUST use Doctrine DB Adapter */
                    $dbAdapter           = $sm->get('Laminas\Db\Adapter\Adapter');
                    $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 'employee','username','password', '?');

                    $authService =
                        /** @todo CAN use lib classes */
                        /*new \Lib\Common\Service\Auth\AuthService(
                        $sm->get('\Lib\Staff\Persistence\Repository\EmployeeRepository'),
                        $sm->get('\Lib\Auth\Persistence\Storage\Storage')
                        );*/
                        new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($sm->get('\Lib\Auth\Persistence\Storage\Storage'));

                    return $authService;
                },
            ),
        );
    }
}
