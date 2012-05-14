<?php

$slimtimer_api_key = '__YOUR_SLIMTIMER_API_KEY__'; // Your SlimTimer API key
$slimtimer_user_id = '__YOUR_SLIMTIMER_USER_ID__'; // Your SlimTimer user_id - get from authentication demo
$slimtimer_user_token = '__YOUR_SLIMTIMER_ACCESS_TOKEN__'; // Your SlimTimer access_token - get from authentication demo

$time_id = '__SOME_TIME_ENTRY_ID__'; 
$start_time = date('Y-m-d H:i:s'); 
$duration_in_seconds = 120; 
$task_id = '__SOME_TASK_ID__';


defined('BASE_PATH') || define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));

set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    realpath(BASE_PATH . '/library')
)));

require_once 'Zend/Loader/Autoloader.php'; // Zend Framework must be in your path
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('SlimTimer_');
$autoloader->registerNamespace('Model_');

try {
    $slimtimerAdapter = new SlimTimer_SlimTimer();
    $slimtimerAdapter->setApiKey($slimtimer_api_key);
    
    $slimtimerAdapter->setUserId($slimtimer_user_id);
    $slimtimerAdapter->setUserToken($slimtimer_user_token);

    $result = $slimtimerAdapter->timeUpdate($time_id, $start_time, $duration_in_seconds, $task_id); // See library/SlimTimer/SlimTimer.php for optional parameters
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit;
}

echo "<pre>\n";
print_r($result);
echo "<pre>";