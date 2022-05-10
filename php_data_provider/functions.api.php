<?php

function compare_value($param,$api_result) {
  global $cc,$callid;
  $branch = '';
  if(count($api_result) == 0 && count($cc[$callid]->UI) == 0) {
    msg("No system variables found.");
    return '.';
  }

  $param = explode(',', $param);
  foreach($param as $val) {
      $op = '';
      unset($key_index);
      list($val,$key) = explode(':',trim($val));                # [v2=success:a]
      $key = trim($key);
      if(strlen($key) != 1) continue;

      $val = trim($val);
      $text = str_replace(array('=','>','<'),'|',$val);
      $i = strpos($text, '|');
      if($i > 0) {
          $op = substr($val, $i, 1);
          list($key_index,$key_value) = explode('|',$text);
      } else {
          $op = '=';
          $key_value = $text;
      }

      $key_index = trim($key_index);
      $key_value = trim($key_value);

      # if value is variable like <ID>; use {} instat of []
      if(substr($key_value, 0, 1) == '{') {
          $key_value = substr($key_value, 1, -1);
          if(@$cc[$callid]->$key_value) {
             $key_value = $cc[$callid]->$key_value;
          } else {
             $key_value = $cc[$callid]->UI[$key_value];
          }
      }

      if($key_index) {
          if(substr($key_index, 0, 1) == '{') {
              $key_index = substr($key_index, 1, -1);
              if(@$cc[$callid]->$key_index) {
                 $key_variable = $cc[$callid]->$key_index;
              } else {
                 $key_variable = $cc[$callid]->UI[$key_index];
              }
          } else {
              $i = substr($key_index, 1);
              if(strtoupper(substr($key_index, 0, 1)) == 'V' && is_numeric($i)) {
                  $key_index = $i - 1;
              }
              $key_variable = $api_result[$key_index];          # associative array
          }
      } else {
          $key_variable = $api_result[0];
      }
      if($op == '=') {
          if(substr($key_value, -1) == '*') {
              if(substr($key_variable,0,strlen($key_value) - 1) == substr($key_value, 0, -1)) {     # [E*]  or [v2=E*]  or [responseCode=E*]  or [<ID>=E*]
                  $branch = $key;
                  break;
              }
          } else {
              if($key_variable == $key_value) {                                                     # [E001]  or  [v2=E001]
                  $branch = $key;
                  break;
              }
          }
      }
      elseif(is_numeric($key_variable) && is_numeric($key_value)) {
          # op '>' and '<' applicable for numeric value only
          if($op == '>' && $key_variable > $key_value) {                                             # [>E001]  or [v2>E001]
              $branch = $key;
              break;
          }
          elseif($op == '<' && $key_variable < $key_value) {                                         # [<E001]  or [v2<E001]
              $branch = $key;
              break;
          }
      }
  } #end of foreach

  msg("Compare: $key_variable $op $key_value");
  if(strlen($branch) == 1) {
      $cc[$callid]->ERR_count = 0;
      msg("Matched. Next node [$branch]");
  } else {
      ++$cc[$callid]->ERR_count;
      if($cc[$callid]->ERR_count < 3) {
         msg("No match found. default node [.]");
         $branch = '.';
      } else {
         msg("No match found. Next node [f]");
         $cc[$callid]->ERR_count = 0;
         # invalid user input 3 times
         $branch = 'f';
      }
  }

return $branch;
}

function get_param_from_text($text) {
$param = '';
  if(substr($text,0,1) == '[') {
      $i = strpos($text,']');
      if($i > 0) {
          $param = substr($text, 1, $i -1);
      }
  }
  return trim($param);
}


function store_api_data($callid, $api_result_str) {
 global $cc;
   if(!$callid) return;
   $api_result_array = @unserialize($api_result_str);
   if(is_array($api_result_array)) {
      msg("Storing Data");
      $cc[$callid]->api_result_array = $api_result_array;
      $api_result_count = count($api_result_array);
      $cc[$callid]->api_result_count = $api_result_count;
   }
}


# convert array or object to array[0] + array[$key]
function obj2array($obj,$i=0) {
  #global $_tmp_ROW;
  if(!$i) $_tmp_ROW = array();
   foreach($obj as $key => $val) {
       if(is_object($val)) {
          //$_tmp_ROW[] = obj2array($val,1);
          //$_tmp_ROW[$key] = obj2array($val,1);
          if(!is_numeric($key) && strlen($key) > 0) {
             foreach ($val as $_key => $_val) {
                if (is_string($_key) && is_string($_val)) {
                  //$_tmp_ROW[$key . '_' . $_key] = $_val;In case of using it check robi last 5 trans
                  $_tmp_ROW[$_key] = $_val;
                }
             }
          }
       } else {
          $key = trim($key);
          $val = trim($val);
          # revove log text or bad-data;
          if(strlen($val) > 80) {
             echo "Trancated value: $val\n";
             $val = 0;
          }
          $_tmp_ROW[] = $val;
          if(!is_numeric($key) && strlen($key) > 0) $_tmp_ROW[$key] = $val;
       }
   }
 return $_tmp_ROW;
}


function array2obj($array){
  foreach($array as $key => $value){
    if(is_array($value)) $array[$key] = array2obj($value);
  }
  return (object) $array;
}



function license_file() {
}


function auth_license_key($key) {
}

function myBase64() {
  $base = array('h','4','a','b','c','3','5','d','B','C','E','F','G','q','r','I','J','m','K','M','T','6','7','8','U','V','W','X',
  'Y','e','f','g','Z','i','D','A','H','j','k','n','o','p','s','t','+','u','v','w','L','x','y','z','1','2','9','0','/','l','N','O','P','Q','R','S');
  return $base;
}

function encode_msg($msg) {
global $base_char;
  $offset = dechex(mt_rand(0,15));
  $msg = $offset . $msg;

  $len = strlen($msg);
  $pad = $len % 3;
  if($pad > 0) {
      $pad = 3 - $pad;
      $msg = str_repeat('=', $pad) . $msg;
      $len += $pad;
  }

  for($i = 0; $i < $len; $i++) {
     $asci = ord(substr($msg, $i, 1));
     $bin_msg .= str_pad(decbin($asci), 8, '0', STR_PAD_LEFT);
  }

  $len *= 8;
  $bin_msg = substr($bin_msg, -4) . substr($bin_msg, 0, -4);

  for($i = 0; $i < $len; $i += 6) {
     $x = bindec(substr($bin_msg, $i, 6)) + hexdec($offset);
     if($x > 63) $x -= 64;
     $encode_msg .= $base_char[$x];
  }
  $encode_msg .= $pad . $offset . '=';

  #msg("Encoded: $encode_msg");
  return $encode_msg;
}

function decode_msg($msg) {
 global $baseR_char;
  $offset = hexdec(substr($msg, -2, 1));
  $pad = substr($msg, -3, 1) + 1;
  $msg = substr($msg, 0, -3);
  $len = strlen($msg);

  for($i = 0; $i < $len; $i++) {
      $x = substr($msg, $i, 1);
      $x = $baseR_char[$x] - $offset;
      if($x < 0) $x += 64;
      $bin_msg .= str_pad(decbin($x), 6, '0', STR_PAD_LEFT);
  }

  $bin_msg = substr($bin_msg, 4) . substr($bin_msg, 0, 4);
  $len *= 6;

  for($i = 0; $i < $len; $i += 8) {
      $decode_msg .= chr(bindec(substr($bin_msg, $i, 8)));
  }

  $decode_msg = substr($decode_msg, $pad);
  #msg("Decode: $decode_msg");
  return $decode_msg;
}


function dialer_key($ip, $port) {
}


function error_logs($type, $msg) {
  $filename="/var/log/ccdblib.error.";
  $t = time();
  $filename.= @date("Y.m", $t);

  $curdate = date("Y-m-d H:i:s", $t);
  $msg1= "$curdate $type=> $msg\n";
  msg($msg1);

  file_put_contents($filename, "$msg1", FILE_APPEND | LOCK_EX);
}


function IVRdebug($str) {
msg($str);
  if(is_array($str) || is_object($str)) $str = print_r($str, true);
  $filename="/var/log/IVR.debug.log";
  $t = time();
  $curdate = date("Y-m-d H:i:s", $t);
  $msg= "$curdate\n$str\n";
  file_put_contents($filename, "$msg", FILE_APPEND | LOCK_EX);

}

function createDynamicArray($array, $from_array)
{
    $cnt = count($from_array);
    if ($cnt == 1) {
        $array = $from_array[0];
        return $array;
    }
    $indx = $from_array[0];
    array_shift($from_array);

    $array[$indx] = createDynamicArray($array, $from_array);

    return $array;
}


#######################  PD Engine ######################
function read_PD_JOIN($sip) {
}


function read_PD_EXIT($sip) {
}


function read_PD($sip) {
}


#######################  SDF functions ######################
function SDF_AuthPIN($param) {
	$account = $param[0];
	$PIN = $param[1];
	$auth = db_select("SELECT IF(SHA2(CONCAT(RIGHT(record_id,4),'$PIN'),0)=TPIN,1,0) res,status,IF(LENGTH(TPIN)=32,TPIN,'') TPIN FROM skill_crm WHERE account_id='$account' LIMIT 1");

  # Convert md5 hash to SHA2
  if($auth->TPIN) {
    if(md5($PIN) == $auth->TPIN) {
       $auth->res = 1;
       db_update("UPDATE skill_crm SET TPIN=SHA2(CONCAT(RIGHT(record_id,4),'$PIN'),0) WHERE account_id='$account' LIMIT 1");
    }
  }

  if(strlen($auth->res) > 0) {
     if($auth->res) {
       $res = 'SUCCESS';
     }
     if($auth->status != 'A') {
       $res = 'INACTIVE';
     }
  }
  return $res;
}


function SDF_ChangePIN($param) {
}


function SDF_SetOTP($param) {
}

function SDF_AuthOTP($param) {
}


function SDF_SelectCRMfields($param) {
}


function SDF_GeoNPA($param) {
}


function SDF_Pbranch() 
{
	$sql = "SELECT id, branch_name AS optionEN, branch_name AS optionBN, branch_code AS optionValue FROM branch WHERE status = 'A' ORDER BY  branch_name";
	$branchData = db_select_array($sql);
	return $branchData;
}

function SDF_ChequeBookPrLog($param)
{
	list($branch_id, $cli, $callid, $refno, $acct, $page_no) = $param;
	
	$log_start_time = date("Y-m-d H:i:s");

	$ciphertext = openssl_encrypt($acct, 'des3', '@gPex9581!dd#mwl', 0, substr('f89a74h248u24e93', 0, 8));
	$ciphertext = strrev($ciphertext);
	$ciphertext = base64_encode($ciphertext);

	$branch_id = getBranchId($branch_id);

	$insertSql = "INSERT INTO log_crm_branch SET log_start_time = '{$log_start_time}', branch_id='{$branch_id}', cli='{$cli}',
				callid = '{$callid}', reference_number = '{$refno}', page_no = '{$page_no}', acc_no = '{$ciphertext}', md_type='S' ";

	if (db_update($insertSql)) {
		return array('responseCode' => 100);
	}
	return array('responseCode' => 101);
}

function SDF_ChequeBookAcLog($param)
{
	list($cli, $callid, $refno, $acct, $page_no) = $param;
	
	$log_start_time = date("Y-m-d H:i:s");
	$ciphertext = openssl_encrypt($acct, 'des3', '@gPex9581!dd#mwl', 0, substr('f89a74h248u24e93', 0, 8));
	$ciphertext = strrev($ciphertext);
	$ciphertext = base64_encode($ciphertext);

	$insertSql = "INSERT INTO log_crm_branch SET log_start_time = '{$log_start_time}', branch_id='', branch_type='D', cli='{$cli}',
				callid = '{$call_id}', reference_number = '{$reference_no}', page_no = '{$page_no}', acc_no = '{$ciphertext}', md_type='S' ";

	if (db_update($insertSql)) {
		return array('responseCode' => 100);
	}
	return array('responseCode' => 101);
}

function SDF_OperatorNumber ($param)
{
    list($operatorId) = $param;
    return array( 'operatorId' => $operatorId );
}
    
function SDF_RfcNumber(){
	$uTime = gettimeofday();
	$refNumber = $uTime['sec'] . $uTime['usec'];
	return array("rfcnumber" => $refNumber);
}

function SDF_TestAPI()
{
	return array('responseCode' => 101);
}

function getBranchId($branch_code)
{
	$sql = "SELECT id FROM branch WHERE branch_code = '$branch_code' LIMIT 1";
	$branchData = db_select_array($sql);
	foreach ($branchData as $data) {
		if (!empty($data['id'])) return $data['id'];
	}
	return null;
}






