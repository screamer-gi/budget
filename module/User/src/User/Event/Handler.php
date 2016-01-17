<?php
namespace User\Event;

use Auth\Service\AdminService;
use Functional as F;
use Lib\Administration\Service\AbstractConfigurationProvider;
use Lib\Administration\Service\AdministrationServiceInterface;
use Lib\Common\Event\Handler\MessageHandler;
use Lib\Common\Service\Translator\AbstractTranslator;
use Lib\Message\Service\AbstractMessageService;

class Handler extends MessageHandler
{
    private $adminService;

    public function  __construct
        ( AbstractMessageService         $messageSender
        , AbstractTranslator             $translator
        , AdministrationServiceInterface $administrationService
        , AdminService                   $adminService
        , AbstractConfigurationProvider  $configService
    ) {
        $this->adminService = $adminService;
        parent::__construct($messageSender, $translator, $administrationService, $configService);
    }

    public function onRestorePassword($service, $params)
    {
        $this->sendEmail
            ( [AbstractTranslator::KEY_PASSWORD_RESTORED_TEXT, ['password' => $params['password']]]
            , AbstractTranslator::KEY_PASSWORD_RESTORED_SUBJECT
            , [$params['user']] );

        $this->sendEmail
            ( [AbstractTranslator::KEY_PASSWORD_RESTORED_FOR_ADMIN_TEXT,    ['user' => $params['user']->fullname]]
            , [AbstractTranslator::KEY_PASSWORD_RESTORED_FOR_ADMIN_SUBJECT, ['user' => $params['user']->fullname]]
            , F\map($this->adminService->getList(), function ($admin) { return $admin->admin; }) );
    }
} 