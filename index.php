<?php

$slimtimer_api_key = ''; // Your SlimTimer API key
$slimtimer_email = ''; // Your SlimTimer Email
$slimtimer_password = ''; // Your SlimTimer Password


defined('BASE_PATH') || define('BASE_PATH', realpath(dirname(__FILE__)));

set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    realpath(BASE_PATH . '/library')
)));

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('SlimTimer_');


$authenticator = new SlimTimer_Authenticator();
$authenticator->setApiKey($slimtimer_api_key);

$authenticator->setEmail($slimtimer_email);
$authenticator->setPassword($slimtimer_password);

if ($authenticator->run() === false) {
    echo "not authenticated";
    exit;
} 


$taskLister = new SlimTimer_TaskLister();
$taskLister->setApiKey($slimtimer_api_key);
$taskLister->setUserId($authenticator->getUserId());
$taskLister->setUserToken($authenticator->getUserToken());

print_r($taskLister->run());

