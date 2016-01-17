<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

include 'init_autoloader.php';

Zend\Mvc\Application::init(include 'config/application.config.php')->run();
