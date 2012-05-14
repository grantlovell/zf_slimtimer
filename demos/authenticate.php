<?php

$slimtimer_api_key = '__YOUR_SLIMTIMER_API_KEY__'; // Your SlimTimer API key
$slimtimer_email = '__YOUR_SLIMTIMER_EMAIL__'; // Your SlimTimer Email
$slimtimer_password = '__YOUR_SLIMTIMER_PASSWORD__'; // Your SlimTimer Password


defined('BASE_PATH') || define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));

set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    realpath(BASE_PATH . '/library')
)));

require_once 'Zend/Loader/Autoloader.php'; // Zend Framework must be in your path
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('SlimTimer_');

try {
    $slimtimerAdapter = new SlimTimer_SlimTimer();
    $slimtimerAdapter->setApiKey($slimtimer_api_key);
    
    $slimtimerAdapter->authenticate($slimtimer_email, $slimtimer_password);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit;
}

echo "User Id: " . $slimtimerAdapter->getUserId() . "<br />\n";
echo "User Token: " . $slimtimerAdapter->getUserToken() . "<br />";