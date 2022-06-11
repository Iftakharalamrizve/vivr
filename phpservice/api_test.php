<?php

include('functions.api.php');
include('db_conf.php');
include('API.php');

db_conn();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
//echo "<pre>";
//echo "API test response: <br>";
//echo "test_api:";
$response = call_extn_api("XSQL:SDF:ChequeBookAcLog(8801737186309,17371863091604311794,1604313330208745,2181761260001,25)", array('CLI'=>'01710859172'), false, '', 'v1234567890', 0);
//$response = call_extn_api("XSQL:SDF_TestAPI()", array('CLI'=>'8801713178138'), false, '', 'v1234567890', 0);
// $response = call_extn_api("HTTP:MWVRCAPI:ACCT=371599209255969,CLI=01672653784,CALLID=8591721601303622,GPIN=6595", array('CLI'=>'01672653784'), false, '', 'v1234567890', 0);
// $response = call_extn_api("HTTP:MWLOSPDE:CID=RC23925,CLI=8801710859172,CALLID=8591721601303622,LTYPE=H", array('CLI'=>'01916100059'), false, '', 'v1234567890', 0);
// $response = call_extn_api("HTTP:MWACCAVR:CLI=8801710859172,CALLID=17108591721601360594,ACCT=2302776781001", array('CLI'=>'01916100059'), false, '', 'v1234567890', 0);
//$response = call_extn_api("HTTP:RBT_ST:CLI=01818739622", array('CLI'=>'01818739622'), false, '', 'v1234567890', 0);


echo "<pre>";
print_r($response);


