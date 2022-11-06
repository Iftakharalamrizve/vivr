<?php
header('Content-Type: text/html; charset=utf-8');

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(1);
$debug = 1;

$RequestData = json_decode(file_get_contents("php://input"),true);



include('db_conf.php');
include('functions.api.php');
include('API.php');
include('function_list_of_action.php');
include('vivr_db_functions.php');
include('vivr_api_functions.php');


$method = isset($RequestData["method"]) ? trim($RequestData["method"]) : "";
$params = isset($RequestData["params"]) ? json_decode(trim($RequestData["params"])) : "";
// $method = 'vivrLog';
// $params = ["2022-11-06 17:38:32","2022-11-06 17:38:32","1686790963","FB","AE",0,"16867909631667756312","EN","","","","I","127.0.0.1",true];

// $response = null;
if(function_exists($method)){
    $response = call_user_func_array($method, $params);
}

die(json_encode($response));
exit;

