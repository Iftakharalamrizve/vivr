<?php
// header('Content-Type: text/html; charset=utf-8');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
echo 123;
exit;
error_reporting(0);
$debug = 0;

$RequestData = json_decode(file_get_contents("php://input"),true);
// $RequestData = $_REQUEST;


function generateApiRequestResponseLog($data,$line)
{
	$path = getcwd () . DIRECTORY_SEPARATOR . 'log' . DIRECTORY_SEPARATOR ;
	$log = 'User: ' . $_SERVER[ 'REMOTE_ADDR' ] . ' - ' . date ( 'F j, Y, g:i a' ) . PHP_EOL .
	    'Request: ' . json_encode ($data) .
	    'Line: ' . $line . PHP_EOL .'--------------------------------------------------------------------------------------' . PHP_EOL;
	//Save string to log, use FILE_APPEND to append.
	file_put_contents ( $path.'log_' . date ( 'j.n.Y' ) . '.txt' , $log, FILE_APPEND );
}

generateApiRequestResponseLog($RequestData,__LINE__);

include('db_conf.php');
include('functions.api.php');
include('API.php');
include('function_list_of_action.php');
include('vivr_db_functions.php');
include('vivr_api_functions.php');

// $method = isset($RequestData["method"]) ? trim($RequestData["method"]) : "";
// $params = isset($RequestData["params"]) ? json_decode(trim($RequestData["params"])) : "";

$method = isset($RequestData["method"]) ? trim($RequestData["method"]) : "";
$params = isset($RequestData["params"]) ? json_decode(trim($RequestData["params"])) : "";


// $response = null;
if(function_exists($method)){
    $response = call_user_func_array($method, $params);
}


die(json_encode($response));
exit;

