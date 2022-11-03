<?php

// include('vivr_db_functions.php');
db_conn();

function getArrayData ( $data_set )
{
    $final_data = array();
    foreach ($data_set as $data) {
        array_push ( $final_data , $data );
    }
    return $final_data;
}

function getVIVRData ( $token , $cli )
{
    $is_valid = true;

    if ( ! preg_match ( '/^[a-zA-Z0-9]{12}$/' , $token ) ) { //12 length alphanum
        $is_valid = false;
    }
    $result = null;

    if ( $is_valid ) {
        $currentTime = date ( 'Y-m-d H:i:s' );
        $sql         = "SELECT * FROM vivr_link WHERE token = '$token' AND cli = '$cli' AND exp_time > '$currentTime' AND status != 'Y'  LIMIT 1";
        $result      = db_select_array ( $sql );
        if ( is_array ( $result ) ) {
            db_update ( "UPDATE vivr_link SET status='Y' WHERE token = '$token' AND cli = '$cli' AND exp_time > '$currentTime' AND status != 'Y' LIMIT 1" );
        }
    }
    $result = getArrayData ( $result );
    return $result;
}


function getFunctionOfAction ( $action , $ivr_id )
{
    $is_valid = true;

    if ( ! preg_match ( '/^[a-zA-Z]{2}$/' , $ivr_id ) ) { //2 length alpha
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[a-zA-Z]{3,4}$/' , $action ) ) { //4 length alpha
        $is_valid = false;
    }

    $function_name = null;

    if ( $is_valid ) {
        $sql           = "SELECT function from vivr_action_wise_functions WHERE ivr_id = '$ivr_id' AND task = '$action'";
        $function_name = db_select_one ( $sql );
    }

    return $function_name;
}

function getDefaultAction ( $ivr_id )
{
    $is_valid = true;

    if ( ! preg_match ( '/^[a-zA-Z]{2}$/' , $ivr_id ) ) { //2 length alpha
        $is_valid = false;
    }

    $action_name = null;
    if ( $is_valid ) {
        $sql         = "SELECT task from vivr_default_task WHERE ivr_id = '$ivr_id'";
        $action_name = db_select_one ( $sql );
    }

    return $action_name;
}

function getDefaultPageID ( $ivr_id )
{
    $is_valid = true;

    if ( ! preg_match ( '/^[a-zA-Z]{2}$/' , $ivr_id ) ) { //2 length alpha
        $is_valid = false;
    }

    $page_id = null;
    if ( $is_valid ) {
        $sql     = "SELECT page_id FROM vivr_default_page WHERE ivr_id='$ivr_id' ";
        $page_id = db_select_one ( $sql );
    }

    return $page_id;
}

function getPageIdFromButton ( $btn_id )
{
    $is_valid = true;
    if ( ! preg_match ( '/^[0-9]{10}$/' , $btn_id ) ) { //10 length digit
        $is_valid = false;
    }
    $page_id = null;

    if ( $is_valid ) {
        $sql     = "SELECT page_id from vivr_pages_of_buttons WHERE button_id = '$btn_id'";
        $page_id = db_select_one ( $sql );
    }
    return $page_id;
}

function getNavigationPage ( $navigation_type )
{
    $is_valid = true;

    if ( ! preg_match ( '/^[a-zA-Z_]{8,15}$/' , $navigation_type ) ) { //8-14 length char with underscore
        $is_valid = false;
    }
    $previous_page_elements = null;

    if ( $is_valid ) {
        $previous_page_elements = db_select_array ( "SELECT * FROM vivr_page_elements WHERE name='$navigation_type' limit 1" );
    }

    return $previous_page_elements;
}

function getPageFromPageId ( $page_id )
{
    $is_valid = true;
    if ( ! preg_match ( '/^[0-9]{10}$/' , $page_id ) ) { //10 length digit
        $is_valid = false;
    }

    $page_data = null;

    if ( $is_valid ) {
        $sql       = "SELECT * FROM vivr_pages WHERE page_id='$page_id' LIMIT 1";
        $page_data = db_select_array ( $sql );
    }

    return $page_data;
}

function getPageElementsFromPageId ( $page_id )
{
    $is_valid = true;
    if ( ! preg_match ( '/^[0-9]{10}$/' , $page_id ) ) { //10 length digit
        $is_valid = false;
    }
    $page_elements = null;

    if ( $is_valid ) {
        $sql           = "SELECT * FROM vivr_page_elements WHERE page_id='$page_id' AND is_visible='Y' ORDER BY element_order";
        $page_elements = db_select_array ( $sql );
    }

    return $page_elements;
}

function getComparingData ( $element_id )
{
    $is_valid = true;
    if ( ! preg_match ( '/^[0-9]{10}$/' , $element_id ) ) { //10 length digit
        $is_valid = false;
    }

    $comparing_data = null;
    if ( $is_valid ) {
        $sql            = "SELECT * FROM vivr_elements_api_comparison WHERE element_id = '$element_id' ORDER BY comparing_order";
        $comparing_data = db_select_array ( $sql );
    }

    return $comparing_data;
}

function getElementsApiKeyData ( $element_id )
{
    $is_valid = true;
    if ( ! preg_match ( '/^[0-9]{10}$/' , $element_id ) ) { //10 length digit
        $is_valid = false;
    }

    $key_data = null;

    if ( $is_valid ) {
        $sql      = "SELECT * FROM vivr_elements_api_keys WHERE element_id = '$element_id' ORDER BY key_order";
        $key_data = db_select_array ( $sql );
    }

    return $key_data;
}

function getElementCalculationData ( $element_id )
{
    $is_valid = true;
    if ( ! preg_match ( '/^[0-9]{10}$/' , $element_id ) ) { //10 length digit
        $is_valid = false;
    }
    $calculation_data = null;

    if ( $is_valid ) {
        $sql              = "SELECT * FROM vivr_elements_api_calculation WHERE element_id = '$element_id' ORDER BY calculation_order";
        $calculation_data = db_select_array ( $sql );
    }

    return $calculation_data;
}


function checkUser ( $cli , $pin )
{
    $is_valid = true;
    if ( ! preg_match ( '/^[0-9]{10}$/' , $cli ) ) { //10 length digit
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[0-9]{6}$/' , $pin ) ) { //6 length digit
        $is_valid = false;
    }

    $data = null;

    if ( $is_valid ) {
        $currentTime = date ( 'Y-m-d H:i:s' );
        $sql         = "SELECT * FROM vivr_token WHERE auth_code = '$pin' AND cli = '$cli' AND exp_time > '$currentTime' AND status != 'Y' LIMIT 1";
        $data        = db_select_array ( $sql );
        if ( is_array ( $data ) ) {
            // db_update("DELETE FROM vivr_token WHERE auth_code = '$pin' AND cli = '$cli' LIMIT 1");
            db_update ( "UPDATE vivr_token SET status = 'Y' WHERE auth_code = '$pin' AND cli = '$cli' AND exp_time > '$currentTime' AND status != 'Y' LIMIT 1" );
        }
    }

    return $data;
}

function generatePIN ( $cli , $plan , $ivr_id , $auth_code , $ip = '' , $session_id = '' , $send_status = 'N' )
{
    $is_valid = true;

    if ( ! preg_match ( '/^[0-9]{10}$/' , $cli ) ) { //10 length digit
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[0-9]{6}$/' , $auth_code ) ) { //6 length digit
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[a-zA-Z]{2}$/' , $ivr_id ) ) { //2 length alpha
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[a-z]{0,10}$/i' , $plan ) ) {
        $is_valid = false;
    }


    $token = '';

    $did      = '16234';
    $language = 'BN';
    $otp_time = getOtpExpTime ();
    $exp_time = date ( 'Y-m-d H:i:s' , strtotime ( $otp_time ) );
    // $exp_time = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    if ( $is_valid ) {
        $sql = "INSERT INTO vivr_token SET token = '$token', auth_code = '$auth_code' , cli = '$cli', did = '$did', plan = '$plan',
            ivr_id = '$ivr_id', language = '$language', exp_time = '$exp_time', ip = '$ip', session_id = '$session_id', status='N', 
			send_status = '$send_status' ";

        return db_update ( $sql );
    }
    return false;
}

function getOtpExpTime ()
{
    $vivr_settings = db_select_array ( "SELECT * FROM module_settings as ms left join modules as m ON m.module_id = ms.module_id WHERE m.code='SIVR'" );
    foreach ($vivr_settings as $key => $item) {
        if ( $item->name == 'otp_time' ) {
            $session_timeout = $item->value;
            return '+' . $session_timeout . ' seconds';
        }
    }
    return '+5 minutes';
}

function deletePIN ( $cli , $auth_code )
{
    $is_valid = true;

    if ( ! preg_match ( '/^[0-9]{10}$/' , $cli ) ) { //10 length digit
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[0-9]{6}$/' , $auth_code ) ) { //6 length digit
        $is_valid = false;
    }
    $data = false;

    if ( $is_valid ) {
        $sql  = "DELETE FROM vivr_token WHERE auth_code = '$auth_code' AND cli = '$cli' LIMIT 1";
        $data = db_update ( $sql );
    }

    return $data;
}

function logCustomerJourney ( $cli , $module_type , $module_subtype , $log_time , $journey_id )
{
    $is_valid = true;

    if ( ! preg_match ( '/^[0-9]{10}$/' , $cli ) ) { //10 length digit
        $is_valid = false;
    }
    if ( ! preg_match ( '/^[a-zA-Z]{2}$/' , $module_type ) ) { //2 length alpha
        $is_valid = false;
    }
    if ( ! preg_match ( '/^[a-zA-Z]{2}$/' , $module_subtype ) ) { //2 length alpha
        $is_valid = false;
    }
    if ( ! strtotime ( $log_time ) ) { //datetime format
        $is_valid = false;
    }
    if ( ! preg_match ( '/^[0-9]{20}$/' , $journey_id ) ) { //20 length digit
        $is_valid = false;
    }

    $data = false;

    if ( $is_valid ) {
        $sql  = "INSERT INTO log_customer_journey  SET customer_id = '$cli', module_type = '$module_type', module_sub_type = '$module_subtype',
            log_time = '$log_time' , journey_id = '$journey_id'";
        $data = db_update ( $sql );
    }

    return $data;
}

function vivrLog ( $start_time , $stop_time , $cli , $did , $ivr_id , $time_in_ivr , $session_id , $language , $skill_id , $skill_status , $ice_feedback , $source , $ip , $is_registered )
{
    $is_valid = true;
    if ( ! strtotime ( $start_time ) ) { //datetime format
        $is_valid = false;
    }
    if ( ! strtotime ( $stop_time ) ) { //datetime format
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[0-9]{10}$/' , $cli ) ) { //10 length digit
        $is_valid = false;
    }
    /*if (!preg_match('/^[0-9]{1,15}$/', $did) || ) { //15 length digit
        $is_valid = false;
    }*/
    if ( ! preg_match ( '/^[a-zA-Z0-9]{1,15}$/' , $did ) ) { //15 length digit  or 10 digit  string
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[a-zA-Z]{2}$/' , $ivr_id ) ) { //2 length alpha
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[0-9]{1,4}$/' , $time_in_ivr ) ) {
        $is_valid = false;
    }
    if ( ! preg_match ( '/^[0-9]{20}$/' , $session_id ) ) { //20 length digit
        $is_valid = false;
    }

    if ( ! in_array ( $language , array( 'BN' , 'EN' ) ) ) {
        $is_valid = false;
    }
    if ( $skill_id != '' ) {
        if ( ! preg_match ( '/^[a-zA-Z0-9]{2}$/' , $skill_id ) ) { //2 length number or charecter
            $is_valid = false;
        }
    }
    if ( $skill_status != '' ) {
        if ( ! preg_match ( '/^[a-zA-Z0-9]{1}$/' , $skill_status ) ) { //1 length number or charecter
            $is_valid = false;
        }
    }
    if ( $ice_feedback != '' ) {
        if ( ! in_array ( $ice_feedback , array( 'Y' , 'N' ) ) ) {
            $is_valid = false;
        }
    }
    if ( $source != '' ) {
        if ( ! in_array ( $source , array( 'I' , 'W' ) ) ) { // IVR or WEB
            $is_valid = false;
        }
    }
    if ( ! preg_match ( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/' , $ip ) ) { //ipv4 address
        $is_valid = false;
    }

    $repeated_call = 'N';
    if ( checkRepeatedCall ( $cli ) ) {
        $repeated_call = 'Y';
    }

    $data = false;

    if ( $is_valid ) {
        $sql = "INSERT INTO vivr_log SET start_time = '$start_time', stop_time = '$stop_time', cli = '$cli', did = '$did' ,
            ivr_id = '$ivr_id', time_in_ivr = '$time_in_ivr', session_id = '$session_id', language = '$language',
            skill_id = '$skill_id', skill_status = '$skill_status', ice_feedback = '$ice_feedback', source = '$source', 
            ip = '$ip', repeated_call='$repeated_call', is_registered='$is_registered'";

        $data = db_update ( $sql );
    }
    return $data;
}

function checkRepeatedCall ( $cli )
{
    $date      = date ( 'Y-m-d' );
    $startTime = $date . ' 00:00:00';
    $endTime   = $date . ' 23:59:59';
    $sql       = "SELECT count(session_id) as total_count FROM vivr_log WHERE cli = '{$cli}' and start_time BETWEEN '{$startTime}' AND '{$endTime}'";
    $result    = db_select_array ( $sql );
    if ( is_array ( $result ) ) {
        $result = reset ( $result );
        if ( $result->total_count > 1 ) return true;
    }
    return false;
}

function updateVivrLog ( $stop_time , $time_in_ivr , $session_id )
{
    $is_valid = true;
    if ( ! strtotime ( $stop_time ) ) { //datetime format
        $is_valid = false;
    }
    if ( ! preg_match ( '/^[0-9]{1,4}$/' , $time_in_ivr ) ) {
        $is_valid = false;
    }
    if ( ! preg_match ( '/^[0-9]{20}$/' , $session_id ) ) { //20 length digit
        $is_valid = false;
    }

    $data = false;
    if ( $is_valid ) {
        $sql  = "UPDATE vivr_log set stop_time='$stop_time', time_in_ivr = $time_in_ivr WHERE session_id = '$session_id'";
        $data = db_update ( $sql );
    }
    return $data;
}

function logVivrJourney ( $log_time , $from_page , $to_page , $session_id , $ivr_id , $dtmf , $time_in_ivr , $status_flag , $ip )
{
    $is_valid = true;

    if ( ! strtotime ( $log_time ) ) { //datetime format
        $is_valid = false;
    }
    if ( $from_page != '' ) {
        if ( ! preg_match ( '/^[0-9]{10}$/' , $from_page ) ) { //10 length digit
            $is_valid = false;
        }
    }
    if ( $to_page != '' ) {
        if ( ! preg_match ( '/^[0-9]{10}$/' , $to_page ) ) { //10 length digit
            $is_valid = false;
        }
    }
    if ( ! preg_match ( '/^[a-zA-Z]{2}$/' , $ivr_id ) ) { //2 length alpha
        $is_valid = false;
    }
    if ( $dtmf != '' ) {
        if ( ! preg_match ( '/^[a-zA-Z0-9]{1}$/' , $dtmf ) ) { //1 length number or charecter
            $is_valid = false;
        }
    }
    if ( ! preg_match ( '/^[0-9]{20}$/' , $session_id ) ) { //20 length digit
        $is_valid = false;
    }
    if ( ! preg_match ( '/^[0-9]{0,4}$/' , $time_in_ivr ) ) {
        $is_valid = false;
    }
    if ( $status_flag != '' ) {
        if ( ! preg_match ( '/^[a-zA-Z0-9]{1}$/' , $status_flag ) ) { //1 length number or charecter
            $is_valid = false;
        }
    }
    if ( ! preg_match ( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/' , $ip ) ) { //ipv4 address
        $is_valid = false;
    }

    $data = false;

    if ( $is_valid ) {
        $sql = "INSERT INTO vivr_journey  SET log_time = '$log_time' , branch_title_id = '$from_page', service_title_id = '$to_page',
            session_id = '$session_id', ivr_id = '$ivr_id', dtmf = '$dtmf', time_in_ivr = '$time_in_ivr', status_flag = '$status_flag'";

        $data = db_update ( $sql );
    }

    return $data;
}

function callAgent ( $cli , $did , $node , $txt )
{
    //db_update("DELETE FROM tb_mpf WHERE MPF1='$cli' AND MPF6='VMA'");
    //db_update("INSERT INTO tb_mpf SET MPF1='$cli', MPF2='" . time() . "', MPF3='$did', MPF4='$node', MPF5='$txt', MPF6='VMA'");
}

function iceFeedback ( $stop_time , $time_in_ivr , $session_id , $feedback )
{
    $is_valid = true;
    if ( $feedback != '' ) {
        if ( ! in_array ( $feedback , array( 'Y' , 'N' ) ) ) {
            $is_valid = false;
        }
    }

    if ( ! preg_match ( '/^[0-9]{20}$/' , $session_id ) ) { //20 length digit
        $is_valid = false;
    }

    if ( ! preg_match ( '/^[0-9]{1,5}$/' , $time_in_ivr ) ) {
        $is_valid = false;
    }

    if ( ! strtotime ( $stop_time ) ) { //datetime format
        $is_valid = false;
    }

    $data = false;
    if ( $is_valid ) {
        $sql  = "UPDATE vivr_log set stop_time='$stop_time', time_in_ivr = $time_in_ivr, ice_feedback = '$feedback' WHERE session_id = '$session_id'";
        $data = db_update ( $sql );
    }

    return $data;
}

function getBulletinMessage ()
{
    $msg = false;
    $sql = "SELECT * FROM `sticky_notes` WHERE type='S' and `status`='S' ORDER BY created_at desc LIMIT 1";
    $msg = db_select_array ( $sql );
    return $msg;
}

function getDynamicPageData ( $element_id )
{
    $msg = false;
    $sql = "SELECT * FROM vivr_api_compare WHERE element_id='$element_id'";
    $msg = db_select_array ( $sql );
    return $msg;
}

function setLogoutType ( $session_id , $type , $time_in_ivr )
{
    $sql  = "UPDATE vivr_log set time_in_ivr = $time_in_ivr, logout_type = '$type' WHERE session_id = '$session_id'";
    $data = db_update ( $sql );
    return $data;
}

function validVivrShortLink($token, $ip)
{
    $is_valid = false;
    $result = null;

	$currentTime = date("Y-m-d H:i:s");
	$sql = "SELECT * FROM vivr_link WHERE short_code='$token' AND short_lnk_status !='Y' AND short_code_exp > '$currentTime' LIMIT 1";
	// echo $sql;
	$result = db_select_array($sql,1);
	if (is_array($result)) {
	
		db_update("UPDATE vivr_link SET short_lnk_status='Y', ip='$ip', req_time='$currentTime' WHERE short_code='$token' AND short_code_exp > '$currentTime' AND short_lnk_status != 'Y' LIMIT 1");
	}    

    $result = getArrayData($result);
    return $result;
}

function systemRequetLimit($param = null){
   $currentMonth=date("m");
   $sql       = "SELECT COUNT(*) as total FROM vivr_link WHERE MONTH(log_time) = $currentMonth";
   $result    = db_select_array ( $sql );
   return $result;
}
function throttleFunction($param){

     //perminute request limit check
     $throttleStatus = true ;
     if(is_array($param)){
     
        $ip = $param[0];
        $cli = $param[1];
        $ts = time();
        $cli_ts = $ts - 90;
        $cli_ts_24 = $ts - 86400;
        $cli_ts_48 = $ts - 86400 * 2;
        $log_time = date ( 'Y-m-d H:i:s' );
        $upd = db_update("DELETE FROM vivr_token_req_summary WHERE ip='$ip' AND tstamp < $cli_ts_48;");
 
         $count = db_select_one("SELECT COUNT(*) AS cnt FROM vivr_token_req_summary WHERE cli='$cli' AND tstamp > $cli_ts_24 LIMIT 1;");
         if ($count > 10) {
            $throttleStatus = false ;
         }
 
         $count = db_select_one("SELECT COUNT(*) AS cnt FROM vivr_token_req_summary WHERE cli='$cli' AND tstamp > $cli_ts LIMIT 1;");
         if ($count > 1) {
            $throttleStatus = false ;
         }
 
         $count = db_select_one("SELECT COUNT(*) AS cnt FROM vivr_token_req_summary WHERE ip='$ip' AND tstamp > $cli_ts_24 LIMIT 1;");
         if ($count > 250) {
            $throttleStatus = false ;
         }
 
         $count = db_select_one("SELECT COUNT(*) AS cnt FROM vivr_token_req_summary WHERE ip='$ip' AND tstamp > $cli_ts LIMIT 1;");
         if ($count > 5) {
            $throttleStatus = false ;
         }
         $upd = db_update("INSERT INTO vivr_token_req_summary(cli,ip,tstamp) VALUES ($cli,'$ip',$ts);");
     }

    return ['status'=>$throttleStatus];
}

function getIVRGeneratedLink ($dialto,$dialfrom,$lang,$ivrid)
{

    global $g;
    $plan     = ''; // blank
    $smsfrom  = ''; // blank

    $vivr_settings   = db_select_array ( "SELECT * FROM module_settings as ms left join modules as m ON m.module_id = ms.module_id WHERE m.code='SIVR' " );
    $domain          = '';
    $session_timeout = '';
    foreach ($vivr_settings as $key => $item) {
        if ( $item->name == 'session_timeout' )
            $session_timeout = $item->value;
        elseif ( $item->name == 'sivr_domain' )
            $domain = $item->value;
    }

    $token    = substr ( md5 ( 'cPlex-S-IvR' . rand ( 10 , 999999 ) . 'End!' ) , 10 , 12 );
    $log_time = date ( 'Y-m-d H:i:s' );
    $exp_time = date ( 'Y-m-d H:i:s' , strtotime ( $log_time ) + $session_timeout );

    //generate random string
    $allowStr        = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $short_link_code = '';
    for ($i = 0; $i < 6; $i ++) {
        $short_link_code .= $allowStr[ rand ( 0 , strlen ( $allowStr ) - 1 ) ];
    }

    // $sql = "INSERT INTO vivr_link SET log_time='$log_time', token='$token', cli='$dialto', did='$dialfrom', plan='$plan', " .
    // "ivr_id='$ivrid', language='$lang', exp_time='$exp_time', short_code='$short_link_code', short_code_exp='$exp_time'";
    // return $sql;
    // exit;
    $flag = db_update ( "INSERT INTO vivr_link SET log_time='$log_time', token='$token', cli='$dialto', did='$dialfrom', plan='$plan', " .
                        "ivr_id='$ivrid', language='$lang', exp_time='$exp_time', short_code='$short_link_code', short_code_exp='$exp_time'" );
    
    $res      = 'F';
    $new_sivr = '';
    if ( ctype_digit ( $dialto ) && ! empty( $short_link_code ) && $flag == 1 ) {
        $res      = 'S';
        $new_sivr = $short_link_code;
    }

    $row             = array();
    $row[ 'status' ] = $res;
    $row[ 'token' ] = $token;
    $row[ 'url' ]    = $new_sivr;

    return $row;
}
