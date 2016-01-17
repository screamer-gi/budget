<?php

namespace Common\Service\Translator;

abstract class AbstractTranslator
{
    const KEY_TASK_CREATE_TEXT    = 'Вам назначена задача ":subject:".';
    const KEY_TASK_CREATE_SUBJECT = 'Новая задача ":subject:".';
    const KEY_TASK_REWORK_TEXT    = 'Задача ":subject:" отклонена. Причина: :review:';
    const KEY_TASK_REWORK_SUBJECT = 'Ваша задача ":subject:" отправлена на доработку.';
    const KEY_TASK_FINISHED_TEXT    = 'Задача ":subject:" выполнена';
    const KEY_TASK_FINISHED_SUBJECT = 'Ваша задача ":subject:" выполнена.';
    const KEY_TASK_VERIFIED_TEXT    = 'Задача ":subject:" проверена.';
    const KEY_TASK_VERIFIED_SUBJECT = 'Ваша задача ":subject:" проверена.';
    const KEY_TASK_IDLE_TEXT        = 'Ваш подчиненный :employee: не получает задачи больше :days: дней.';
    const KEY_TASK_IDLE_SUBJECT     = 'Отсутствие задач у сотрудника.';
    const KEY_TASK_IDLE_UP_TEXT     = 'Ваш подчиненный :head: не назначает задачи, из-за чего его подчиненный :employee: больше :days: дней простаивает.';
    const KEY_TASK_IDLE_UP_SUBJECT  = 'Отсутствие задач у сотрудника по вине Вашего подчиненного.';

    const KEY_REPORT_CREATE_TEXT    = 'Был создан отчет ":subject:". Автор: :author:.';
    const KEY_REPORT_CREATE_SUBJECT = 'Отчет за :date:.';

    const KEY_QUOTE_PUBLICATION_TEXT    = 'Цитата ":quote:" автора :author: ждёт решения.';
    const KEY_QUOTE_PUBLICATION_SUBJECT = 'Запрос на публикацию цитаты.';
    const KEY_QUOTE_REJECT_TEXT         = 'Цитата ":quote:" отклонена руководителем.';
    const KEY_QUOTE_REJECT_SUBJECT      = 'Цитата отклонена';

    const KEY_DOCUMENT_NEGOTIATE_TEXT    = 'Документ :name: должен быть Вами согласован.';
    const KEY_DOCUMENT_NEGOTIATE_SUBJECT = 'Документ на согласование.';
    const KEY_DOCUMENT_AGREED_TEXT       = 'Документ :name: подписан.';
    const KEY_DOCUMENT_AGREED_SUBJECT    = 'Документ подписан.';
    const KEY_DOCUMENT_DISAGREE_TEXT     = 'Документ :name: отклонен по причине: ":review:".';
    const KEY_DOCUMENT_DISAGREE_SUBJECT  = 'Документ отклонен.';
    const KEY_DOCUMENT_SIGNATURE_TEXT    = 'Документ :name: нужно подписать.';
    const KEY_DOCUMENT_SIGNATURE_SUBJECT = 'Документ на подписании.';

    const KEY_JOB_DESC_TEXT              = 'Для Вашей должности создана новая должностная инструкция ":name:", Вам необходимо с ней ознакомиться.';
    const KEY_JOB_DESC_SUBJECT           = 'Новая должностная инструкция';
    const KEY_JOB_DESC_ACCEPT_TEXT       = 'Пользователь :employee: ознакомился с должностной инструкцией ":name:".';
    const KEY_JOB_DESC_ACCEPT_SUBJECT    = 'Ознакомление с должностной инструкцией';

    const KEY_EVENT_CREATE_TEXT             = 'Вы приглашены на событие ":text:".';
    const KEY_EVENT_CREATE_SUBJECT          = 'Новое событие :subject:.';
    const KEY_EVENT_PARTICIPANT_ADD_TEXT    = 'Вы добавлены в группу события ":subject:".';
    const KEY_EVENT_PARTICIPANT_ADD_SUBJECT = 'Добавление в группу события ":subject:".';

    const KEY_REQUEST_ASSIGN_TEXT             = 'Поступила заявка: ":subject:"';
    const KEY_REQUEST_ASSIGN_SUBJECT          = 'Новая заявка';
    const KEY_REQUEST_RESULT_ACCEPTED_TEXT    = 'Результат выполнения заявки ":subject:" принят.';
    const KEY_REQUEST_RESULT_ACCEPTED_SUBJECT = 'Результат заявки принят.';
    const KEY_REQUEST_RESULT_REJECTED_TEXT    = 'Результат выполнения заявки ":subject:" не принят. Причина: :review:';
    const KEY_REQUEST_RESULT_REJECTED_SUBJECT = 'Результат заявки не принят.';
    const KEY_REQUEST_DONE_TEXT               = 'Заявка ":subject:" выполнена, нужно принять или отклонить результат.';
    const KEY_REQUEST_DONE_SUBJECT            = 'Заявка ":subject:" выполнена.';
    const KEY_REQUEST_SIGNATURE_TEXT          = 'Нужна ваша подпись для заявки ":subject:".';
    const KEY_REQUEST_SIGNATURE_SUBJECT       = 'Подписание заявки.';
    const KEY_REQUEST_REJECTION_TEXT          = 'Заявка ":subject:" отклонена по причине: ":review:"';
    const KEY_REQUEST_REJECTION_SUBJECT       = 'Заявка ":subject:" отклонена.';
    const KEY_REQUEST_NEGOTIATION_TEXT        = 'Заявка ":subject:" отправлена вам на согласование.';
    const KEY_REQUEST_NEGOTIATION_SUBJECT     = 'Заявка ":subject:" на согласовании.';

    const KEY_PASSWORD_RESTORED_TEXT              = 'Ваш новый пароль: :password:';
    const KEY_PASSWORD_RESTORED_SUBJECT           = 'Восстановление пароля Workitoria';
    const KEY_PASSWORD_RESTORED_FOR_ADMIN_TEXT    = 'Пользователь :user: восстановил пароль.';
    const KEY_PASSWORD_RESTORED_FOR_ADMIN_SUBJECT = 'Пользователь :user: восстановил пароль.';

    const KEY_MESSAGE_RECEIVED_TEXT               = 'Вы получили новое сообщение с темой ":subject:"';
    const KEY_MESSAGE_RECEIVED_SUBJECT            = 'Новое сообщение';
    const KEY_FEEDBACK_RECEIVED_TEXT              = 'Вы получили сообщение обратной связи с темой ":subject:"';
    const KEY_FEEDBACK_RECEIVED_SUBJECT           = 'Обратная связь';

    const KEY_SURVEY_CREATED_TEXT                 = 'Создан новый опрос: ":question:"';
    const KEY_SURVEY_CREATED_SUBJECT              = 'Новый опрос';

    const KEY_REMIND_TEXT                         = "Напоминание: :subject:\n:text: ";
    const KEY_REMIND_SUBJECT                      = 'Напоминание';

    public abstract function translate($key, array $params = []);
}