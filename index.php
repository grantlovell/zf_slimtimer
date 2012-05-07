<?php

defined('BASE_PATH') || define('BASE_PATH', realpath(dirname(__FILE__)));

set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    realpath(BASE_PATH . '/library')
)));

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('SlimTimer_');


$authenticator = new SlimTimer_Authenticator();
$authenticator->setApiKey('d1b1f66a136b3dc3d2b09161f68ba1');

$authenticator->setEmail('c.keithlin@chromemedia.com');
$authenticator->setPassword('Bru5ucr9');

if ($authenticator->run() === false) {
    echo "not authenticated";
    exit;
} 


$taskLister = new SlimTimer_TaskLister();
$taskLister->setApiKey('d1b1f66a136b3dc3d2b09161f68ba1');
$taskLister->setUserId($authenticator->getUserId());
$taskLister->setUserToken($authenticator->getUserToken());

print_r($taskLister->run());

