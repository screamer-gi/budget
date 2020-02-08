<?php

if (strpos($_SERVER['REMOTE_ADDR'], '172.') !== 0) {
    http_response_code(403);
    die('Access denied');
}

$cfg['MaxTableList'] = 500;
$cfg['MaxNavigationItems'] = 500;
$cfg['ShowDbStructureCreation'] = true;

$i = 0;

/**
 * Budget
 */
$i++;
$cfg['Servers'][$i]['verbose'] = 'Budget';
$cfg['Servers'][$i]['host'] = 'db';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = 'budget';
$cfg['Servers'][$i]['auth_type'] = 'config';

/**
 * Budget test
 */
$i++;
$cfg['Servers'][$i]['verbose'] = 'Budget test';
$cfg['Servers'][$i]['host'] = 'db-test';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = 'budget';
$cfg['Servers'][$i]['auth_type'] = 'config';

