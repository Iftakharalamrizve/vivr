<?php

function external_api_call($cli, $call_id, $external_api, $input_name = null, $input_value = null)
{
	$is_valid = true;
	$fnf = null;
	$partner = null;
	$scard = null;
    if (!preg_match('/^[0-9]{10}$/', $cli)) { //10 length digit
        $is_valid = false;
    }
    if ($input_value != null) {
        if ($input_name == "fnf") {
			$fnf = $input_value;
            if (!preg_match('/^[0-9]{10}$/', $input_value)) { //10 length digit                
                $is_valid = false;
            }
        }
        if ($input_name == "partner") {
			$partner = $input_value;
            if (!preg_match('/^[0-9]{10}$/', $input_value)) { //10 length digit
                $is_valid = false;
            }
        }
        if ($input_name == "scard") {
			$scard = $input_value;
            if (!preg_match('/^[0-9]{16}$/', $input_value)) { //16 length digit                
                $is_valid = false;
            }
        }
    }

    if ($is_valid) {
		$UI = array('CLI' => $cli);
		$ivr_debug = false;
		$callid = $call_id;
		$url = str_replace("<CLI>", $cli, $external_api);
        if ($fnf != null) $url = str_replace("<fnf>", $fnf, $url);
        if ($partner != null) $url = str_replace("<partner>", $url);
        if ($scard != null) $url = str_replace("<scard>", $scard, $url);
		$api_response = call_extn_api($url, $UI, $ivr_debug, "", $callid, 0);
		return $api_response;
	}
    return null;
}


function SendSMS($cli, $did, $text)
{
	$is_valid = true;
    if (!preg_match('/^[0-9]{10}$/', $cli)) { //10 length digit
        $is_valid = false;
    }

    if ($is_valid) {
		$param = array($cli, $did, $text);
		$dialto = $param[0];
		$sms = $param[2];
		$dialfrom = isset($param[1]) ? trim($param[1]) : "";
		// if (empty($dialfrom)) $dialfrom = 21313;
		if (empty($dialfrom)) $dialfrom = 24786;
		// if (empty($dialfrom)) $dialfrom = 24753;

		if (substr($sms, 0, 3) == '<T-') {
			$smsid = substr($sms, 3, -1);
			$smstext = db_select_one("SELECT sms_body FROM sms_templates WHERE template_id='$smsid' AND status='Y' LIMIT 1");
		} else {
			if (count($param) > 3) {
				unset($param[0]);
				unset($param[1]);
				$sms = implode(",", $param);
			}
			$smstext = $sms;
		}

		$res = 'FAILED';

		if (ctype_digit($dialto) && !empty($smstext)) {
			$id = time() . rand(1000000, 9999999);
			$txt_sms = base64_encode($smstext);
			$txt1 = substr($txt_sms, 0, 255);
			$txt2 = substr($txt_sms, 255);
			if (db_update("INSERT INTO sms_out SET id='$id', sms_to='$dialto', sms_from='$dialfrom', sms_text='".$txt1."', sms_text_2='".$txt2."'")) $res = 'SUCCESS';
		}

		$row = array();
		$row[0] = $res;
		$row[res] = $res;

		return $row;
	}
    
}
