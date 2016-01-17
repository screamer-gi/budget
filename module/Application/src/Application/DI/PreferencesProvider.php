<?php

namespace Application\DI;

use Zend\Di\InstanceManager;
use Zend\ServiceManager\ServiceManager;
use Zend\Di\ServiceLocator;

class PreferencesProvider
{
    public function applyPreferences(InstanceManager $instanceManager, ServiceManager $serviceManager)
    {

        $instanceManager->setParameters(
            'Common\Persistence\Filter\FilterBetween',
            [ 'entityName'      => ''
            , 'column'          => ''
            , 'minParam'  => ''
            , 'maxParam'    => ''
            ]
        );
        $instanceManager->setParameters(
            'Common\Persistence\Filter\FilterJoinBetween',
            [ 'entityName'      => ''
            , 'column'          => ''
            , 'joinColumn'  => ''
            , 'minParam'  => ''
            , 'maxParam'    => ''
            ]
        );
        $instanceManager->setParameters(
            'Application\Filters\FilterJoinField',
            [ 'entityName'      => ''
            , 'field'           => ''
            , 'joinColumn'      => ''
            ]
        );
        $instanceManager->setParameters(
            'Common\Persistence\Filter\FilterEq',
            [ 'entityName'      => ''
            , 'field'           => ''
            ]
        );
        $instanceManager->setParameters(
            'Application\Form\Validator\DateTimeCompare',
            [ 'operation'       => ''
            , 'field'           => ''
            , 'joinColumn'      => ''
            ]
        );
        $instanceManager->setParameters(
            'Common\Presentation\Zend\Form\Validator\DateTimeCompare',
            [ 'operation'       => ''
            //, 'field'           => ''
            //, 'joinColumn'      => ''
            ]
        );

        $instanceManager->setParameters(
            'Common\Presentation\Zend\Form\Validator\NoObjectExists',
            [ 'options'         => []]
        );

        /** ---------------------------------------------------------------------------------------------------------- */
        $instanceManager->setParameters('Common\Presentation\Zend\Controller\CrudActionController'         , ['resourceName' => []]);
        $instanceManager->setParameters('Common\Presentation\Zend\Controller\CreateDeleteActionController' , ['resourceName' => []]);
        $instanceManager->setParameters('Auth\Form\Parameters\AdminListParameters'       , ['data'           => []]);
        $instanceManager->setParameters('Auth\Form\Parameters\RoleParameters'            , ['data'           => []]);
        $instanceManager->setParameters('Common\Service\Parameters\ParametersExtractor'  , ['parameters'     => []]);

        $instanceManager->setParameters('Auth\Presentation\Zend\Form\Parameters\AdminListParameters'            , ['data' => []]);
        $instanceManager->setParameters('Auth\Presentation\Zend\Form\Parameters\RoleParameters'                 , ['data' => []]);
        $instanceManager->setParameters('Common\Presentation\Zend\Form\Parameters\AbstractParameters'           , ['data' => []]);

        $instanceManager->addTypePreference('Auth\Service\AuthInterface', 'Application\Service\Zend\AuthService');
        $instanceManager->addTypePreference('Auth\Service\AclInterface' , 'Application\Service\Zend\AclService');

        $instanceManager->setParameters(
            'Application\Filters\FilterDateInterval',
            [ 'entityName'      => ''
                , 'column'          => ''
                , 'dateStartParam'  => ''
                , 'dateEndParam'    => ''
            ]
        );
        $instanceManager->setParameters(
            'Application\Filters\FilterColumnsLike',
            [ 'entityName'      => ''
                , 'columnsToSearch' => ''
            ]
        );
        $instanceManager->setParameters(
            'Common\Persistence\Filter\FilterBetween',
            [ 'entityName'      => ''
                , 'column'          => ''
                , 'minParam'  => ''
                , 'maxParam'    => ''
            ]
        );
        $instanceManager->setParameters(
            'Common\Persistence\Filter\FilterJoinBetween',
            [ 'entityName'      => ''
                , 'column'          => ''
                , 'joinColumn'  => ''
                , 'minParam'  => ''
                , 'maxParam'    => ''
            ]
        );
        $instanceManager->setParameters(
            'Application\Filters\FilterJoinField',
            [ 'entityName'      => ''
                , 'field'           => ''
                , 'joinColumn'      => ''
            ]
        );
        $instanceManager->setParameters(
            'Common\Persistence\Filter\FilterEq',
            [ 'entityName'      => ''
                , 'field'           => ''
            ]
        );
        $instanceManager->setParameters(
            'Application\Form\Validator\DateTimeCompare',
            [ 'operation'       => ''
                , 'field'           => ''
                , 'joinColumn'      => ''
            ]
        );
        $instanceManager->setParameters(
            'Common\Presentation\Zend\Form\Validator\DateTimeCompare',
            [ 'operation'       => ''
                //, 'field'           => ''
                //, 'joinColumn'      => ''
            ]
        );

        $instanceManager->setParameters(
            'Common\Presentation\Zend\Form\Validator\NoObjectExists',
            [ 'options'         => []]
        );

        /** ----------------------------------------------------------------------------------------
        /** === USEFUL PREFERENCES =================================================================================== */

        $instanceManager->setParameters(
            'Application\Controller\CrudActionController',
            [ 'params'       => []
            , 'resourceName' => ''
            ]
        );

        $instanceManager->addTypePreference(
            'Common\Service\Translator\AbstractTranslator',
            'Application\Infrastructure\Zend\Translator'
        );
    }
}