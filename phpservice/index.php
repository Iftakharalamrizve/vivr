<?php
header('Content-Type: text/html; charset=utf-8');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(0);
$debug = 0;

$RequestData = json_decode(file_get_contents("php://input"),true);



include('db_conf.php');
include('functions.api.php');
include('API.php');
include('function_list_of_action.php');
include('vivr_db_functions.php');
include('vivr_api_functions.php');


$method = isset($RequestData["method"]) ? trim($RequestData["method"]) : "";
$params = isset($RequestData["params"]) ? json_decode(trim($RequestData["params"])) : "";

// $response = null;
if(function_exists($method)){
    $response = call_user_func_array($method, $params);
}


die(json_encode($response));
exit;

