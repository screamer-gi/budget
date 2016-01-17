<?php
namespace User;

use Lib\Common\Event\AbstractEventManager;

return
    [ AbstractEventManager::EVENT_USER_RESTORE_PASSWORD => [__NAMESPACE__ . '\Event\Handler', 'onRestorePassword']
    ];
