<?php

namespace User\Service;


use Lib\Administration\Service\AdministrationServiceInterface;
use Lib\Auth\Service\AuthInterface;
use Lib\Common\Event\AbstractEventManager;
use Lib\Common\Event\EventTriggerInterface;
use Lib\Common\Service\Translator\AbstractTranslator;
use Lib\Staff\Service\EmployeeRepositoryInterface;
use Message\Service\MessageService;

class UserService
{
    private $employeeRepo;
    private $authService;
    private $messageService;
    private $administrationService;
    private $translator;
    private $eventTrigger;

    public function __construct
        ( EmployeeRepositoryInterface    $employeeRepo
        , AuthInterface                  $authService
        , MessageService                 $messageService
        , AdministrationServiceInterface $administrationService
        , AbstractTranslator             $translator
        , EventTriggerInterface          $eventTrigger )
    {
        $this->employeeRepo          = $employeeRepo;
        $this->authService           = $authService;
        $this->messageService        = $messageService;
        $this->administrationService = $administrationService;
        $this->translator            = $translator;
        $this->eventTrigger          = $eventTrigger;
    }

    public function findSecretQuestionByPhone($phone)
    {
        if ($employee = $this->employeeRepo->findOneBy(['phone' => $phone])) {
            return $employee->secret_question ?: null;
        } else {
            return null;
        }
    }

    public function restorePassword($phone, $answer)
    {
        $employee = $this->employeeRepo->findOneBy(['phone' => $phone]);
        if ($employee && $this->checkSecretQuestionAnswer($employee, $answer)) {
            $password = $this->generatePassword();
            $employee->password = $this->authService->hashPassword($employee, $password);

            $this->eventTrigger->trigger
                ( AbstractEventManager::EVENT_USER_RESTORE_PASSWORD
                , $this
                , ['user' => $employee, 'password' => $password] );

            return true;
        }
        return false;
    }

    private function checkSecretQuestionAnswer($employee, $answer)
    {
        return strcmp(mb_strtolower($answer, 'utf8'), mb_strtolower($employee->secret_answer, 'utf8')) === 0;
    }

    private function generatePassword()
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, 8);
        return $password;
    }
} 