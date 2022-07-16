<?php

function do_api_log($ApiObj)
{
   if ($ApiObj && !empty($ApiObj->conn_method)) {
      $response_time = $ApiObj->total_time>0 ? $ApiObj->total_time : $ApiObj->stop_ts - $ApiObj->start_ts;
      db_update("INSERT INTO log_api_access SET log_time=NOW(), ivr_branch='$ApiObj->ivr_branch', conn_name='$ApiObj->conn_name', ".
         "conn_method='$ApiObj->conn_method', api_function='$ApiObj->api_function', response_code='$ApiObj->response_code', ".
         "response_time='$response_time', transfer_time='$ApiObj->transfer_time', dl_size='$ApiObj->dl_size', ".
         "download_speed='$ApiObj->download_speed', upload_speed='$ApiObj->upload_speed', callid='$ApiObj->callid'");
   }
}

function call_extn_api($url, $UI, $ivr_debug, $ivr_branch, $callid, $soc_delay=0) {

     global $g, $listening_ip;
      $api_result_array = array();
      $conn_method = strtoupper(substr($url,0,4));
      if($ivr_debug) IVRdebug("API Method: $conn_method\nUser Input:");
      if($ivr_debug) IVRdebug($UI);

      $ApiObj = new stdClass();
      $ApiObj->conn_method = $conn_method;
      $ApiObj->ivr_branch = $ivr_branch;
      $ApiObj->callid = $callid;
      $ApiObj->api_function = '';
      $ApiObj->total_time = 0;
      $ApiObj->transfer_time = 0;
      $ApiObj->start_ts = $ApiObj->stop_ts = 0;
      $ApiObj->response_code = '750';
      $ApiObj->dl_size = 0;
      $ApiObj->download_speed = $ApiObj->upload_speed = 0;
      $conn_name = '';

      if($conn_method == 'HTTP') {

          list($dumy,$conn_name,$function,$find_array) = explode(':',$url);      # WSDL-URL is defined in Dadabase
          $ApiObj->conn_name = $conn_name;
          $soap = db_select("SELECT url,credential,pass_credential,return_param,text_delimiter,submit_data,submit_user,submit_pass,return_method,return_data ".
                "FROM ivr_api WHERE conn_name='$conn_name' AND active='Y'");
          //msg($soap);
		  
          if($ivr_debug) IVRdebug($soap);
          $WSDL_URL = $soap->url;
          if(!$WSDL_URL) {
              $error_str = "HTTP conn [$conn_name] not defined";
              error_logs('API', $error_str);
              return;
          }

          $unique_seq = 'Gplex_' . time() . rand(1000,9999);
          $WSDL_URL = str_replace('<UNIQUE_SEQ>', $unique_seq, $WSDL_URL);
          $WSDL_URL = str_replace('<API_LOGIN>', $soap->submit_user, $WSDL_URL);
          $WSDL_URL = str_replace('<API_PASS>', $soap->submit_pass, $WSDL_URL);
//var_dump($fixed_vars);
//var_dump($function);



          $fixed_vars = explode(",", $function);
          if (is_array($fixed_vars) && count($fixed_vars) > 0) {
             foreach ($fixed_vars as $fixed_var) {
                list($key, $val) = explode("=", $fixed_var);
                if (!empty($key) && is_string($key)) {
                   $UI[$key] = $val;
                }
             }
          }

          if (isset($UI['CLI'])) {
             $aCLI = $UI['CLI'];
             $pos = strpos($WSDL_URL, "<NZ_CLI>");
             if ($pos !== false) {
               $nz_cli = $aCLI;
               if (substr($nz_cli, 0, 3) == '880') {
                  $nz_cli = substr($nz_cli, 3);
               } else if (substr($nz_cli, 0, 1) == '0') {
                  $nz_cli = substr($nz_cli, 1);
               }
               $WSDL_URL = str_replace("<NZ_CLI>", $nz_cli, $WSDL_URL);
          }

          $pos = strpos($WSDL_URL, "<FL_CLI>");
          if ($pos !== false) {
             $fl_cli = $aCLI;
             if (substr($fl_cli, 0, 1) == '0') {
                $fl_cli = '88' . $fl_cli;
             } else if (substr($fl_cli, 0, 2) != '88') {
                $fl_cli = '880' . $fl_cli;
             }
             $WSDL_URL = str_replace("<FL_CLI>", $fl_cli, $WSDL_URL);
          }

          $pos = strpos($WSDL_URL, "<LZ_CLI>");
          if ($pos !== false) {
             $lz_cli = $aCLI;
             if (substr($lz_cli, 0, 3) == '880') {
                $lz_cli = substr($lz_cli, 2);
             } else if (substr($lz_cli, 0, 1) != '0') {
                $lz_cli = '0' . $lz_cli;
             }
             $WSDL_URL = str_replace("<LZ_CLI>", $lz_cli, $WSDL_URL);
          }

          }

          if (isset($UI) && is_array($UI)) {
             foreach ($UI as $key => $val) {
                $data = str_replace('<'.$key.'>', $val, $data);
                $WSDL_URL = str_replace('<'.$key.'>', urlencode($val), $WSDL_URL);
             }
          }
		
		  $WSDL_URL = str_replace("rm=I", "rm=S", $WSDL_URL);
		  
          $header = array();
          if ($soap->pass_credential=='B') {
                $header[] = 'Authorization: Basic ' . base64_encode($soap->submit_user.':'.$soap->submit_pass);
                //$header[] = "Content-length: ".strlen($data);
          }
		// echo $WSDL_URL;

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($ch, CURLOPT_TIMEOUT, 20);
          curl_setopt($ch, CURLOPT_URL, $WSDL_URL);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

          if (count($header) > 0) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
          if($ivr_debug) IVRdebug("Calling URL $WSDL_URL:");

          $ApiObj->start_ts = time();
          $result = @curl_exec($ch);
          //$ApiObj->stop_ts - time();
          if ($result === false) {
             $ApiObj->stop_ts = time();
             $ApiObj->total_time = $ApiObj->stop_ts - $ApiObj->start_ts;
             $ApiObj->response_code = curl_errno($ch);
             $error_str = "$conn_name: " . curl_error($ch);
             curl_close($ch);
             error_logs('API', $error_str);
             unset($result);
             do_api_log($ApiObj);
             return;
          } else {
             $header_info = curl_getinfo($ch);
             $ApiObj->total_time = $header_info['total_time'];
             $ApiObj->response_code = $header_info['http_code'];
             $ApiObj->transfer_time = $header_info['starttransfer_time'];
             $ApiObj->dl_size = $header_info['size_download'];
             $ApiObj->download_speed = $header_info['speed_download'];
             $ApiObj->upload_speed = $header_info['speed_upload'];
          }
          curl_close($ch);
          $result = trim($result);

          if($ivr_debug) IVRdebug("Calling SOAP function $function with param:");
          if($ivr_debug) IVRdebug($result);

          if(substr($result,0,2) == '[{' || (substr($result,0,1) == '{' && substr($result,-1) == '}')) {
              $result = json_decode($result, true);
              if($ivr_debug) IVRdebug("Return value is JSON; Converting to Object");

          } elseif(substr($result, 0, 5) == '<?xml') {
              $result = @simplexml_load_string($result);
              if (!empty($soap->return_param)) {
                 if (isset($result->{$soap->return_param})) $result = $result->{$soap->return_param};
              }
              if($ivr_debug) IVRdebug("Return value is XML; Converting to Object");

          } else {
              $result = explode("\n", $result);
              $CSV = 1;
              if($ivr_debug) IVRdebug("Return value is CSV/Lines; Converting to Object");

          }

          if ($soap->return_method == 'N') {
            // Without any processing
            $api_result_array[] = $result;
          } else {
            foreach($result as $val) {
              if(!$CSV) {
                  //$api_result_array[] = obj2array($val);
                  $api_result_array[] = json_decode(json_encode($val), true);
              } else {
                  $api_result_array[] = explode(',',trim( $val));

              }
            }
          }
      }
      else if($conn_method == 'SOAP' || $conn_method == 'XXML') {
          list($dumy,$conn_name,$function,$find_array) = explode(':',$url);      # WSDL-URL is defined in Dadabase
          $ApiObj->conn_name = $conn_name;
          $soap = db_select("SELECT url,credential,pass_credential,submit_method,submit_param,return_param,text_delimiter FROM ivr_api WHERE ".
                "conn_name='$conn_name' AND active='Y'");
          //msg($soap);
          if($ivr_debug) IVRdebug($soap);
          $WSDL_URL = $soap->url;
          if(!$WSDL_URL) {
              $error_str = "SOAP conn [$conn_name] not defined";
              error_logs('API', $error_str);
              return;
          }
          list($function,$soap_var) = explode('(', $function);
          $soap_var = substr($soap_var, 0, -1);
          # $soap_var = explode(',', $soap_var);
          unset($soap_param);
          if($soap->pass_credential == 'Y') {
              $credential_wrapper = array();
              $credential_array = array();
              $soap_credential = $soap->credential;

              # Ex: HeaderIn->MsgId=RQST,ServiceId=EAI-CMS, ...
              $is_wrapper_available = strpos($soap->credential, '->');
              if ($is_wrapper_available !== false) {
                  $credential_wrapper = explode('->', $soap->credential);
                  $credential_cnt = count($credential_wrapper);
                  $soap_credential = $credential_wrapper[$credential_cnt-1];
              }
              $credential = explode(',', $soap_credential);
              foreach($credential as $val) {
                  list($key,$val) = explode('=', $val);
                  $credential_array[$key] = $val;
              }

              if ($is_wrapper_available !== false) {
                  $credential_wrapper[$credential_cnt-1] = $credential_array;
                  $soap_param = createDynamicArray(array(), $credential_wrapper);
              } else {
                  $soap_param = $credential_array;
              }
          }

          # Ex: GetCardInfo(BasicCardEnqDataReq->BasicCardEnqDetReq->Card=<CARD>, ID=123)
          # $soap_var = BasicCardEnqDataReq->BasicCardEnqDetReq->Card=<CARD>, ID=123
          $is_wrapper_available = strpos($soap_var, '->');
          if ($is_wrapper_available !== false) {
              $soap_var_array = explode('->', $soap_var);
              $soap_var_cnt = count($soap_var_array);
              $soap_var = $soap_var_array[$soap_var_cnt-1];
          }

          $request_params = array();
          $soap_var = explode(',', $soap_var);

          foreach($soap_var as $val) {
              $val = trim($val);
              if(strpos($val,'=')) {
                  list($key,$val) = explode('=', $val);
                  $key = trim($key);
                  $val = trim($val);
                  if(substr($val,0,1) == '<') {
                      $val = substr($val,1,-1);
                      $val = $UI[$val];
                  }
                  $request_params[$key] = $val;
              } else {
                  $request_params[$val] = $UI[$val];
              }
          }

          if ($is_wrapper_available !== false) {
              $soap_var_array[$soap_var_cnt-1] = $request_params;
              $request_params = createDynamicArray(array(), $soap_var_array);
          }

          if (is_array($soap_param)) {
                $soap_param = array_merge($soap_param, $request_params);
          } else {
                $soap_param = $request_params;
          }

          if($soap->submit_method == 'C') {                     # Class
                $soap_param = array2obj($soap_param);
          }
          elseif($soap->submit_method == 'J') {                 # JSON
                $soap_param = json_encode($soap_param);
          }
          elseif($soap->submit_method == 'P') {                 # param
                $data = implode(',' , $soap_param);
          }

          if($soap->submit_param) {
                $submit_param = $soap->submit_param;
                $data = new stdClass();
                $data->$submit_param = $soap_param;
          } else {                                              # Array
                $data = $soap_param;
          }
          if($conn_method == 'SOAP') {
             # SOAP connection and function call
             msg("Calling SOAP function $function");
             if($ivr_debug) IVRdebug("Calling SOAP function $function with param:");
             if($ivr_debug) IVRdebug($data);
             $ApiObj->api_function = $function;
             if($function) {
               $ApiObj->start_ts = time();
               try {
                   $client = @new SoapClient($WSDL_URL, array('exceptions' => 1,'connection_timeout' => 20));
                   $result = @$client->$function($data);
                   $ApiObj->stop_ts = time();
                   $ApiObj->total_time = $ApiObj->stop_ts - $ApiObj->start_ts;
                   $ApiObj->response_code = 200;
               } catch (SoapFault $E) {

                   $ApiObj->stop_ts = time();
                   $ApiObj->total_time = $ApiObj->stop_ts - $ApiObj->start_ts;
                   $soap_resp_headers = $client->__getLastResponseHeaders();
                   preg_match("/Content-Length:\s([\d]+)/", $soap_resp_headers, $header_matches);
                   $ApiObj->dl_size = is_array($header_matches) && isset($header_matches[1]) ? $header_matches[1] : 0;
                   preg_match("/HTTP\/\d\.\d\s*\K[\d]+/", $soap_resp_headers, $header_matches);
                   $ApiObj->response_code = is_array($header_matches) && isset($header_matches[0]) ? $header_matches[0] : 0;
                   do_api_log($ApiObj);

                   $error_str = "SOAP Error: fn: $function; $E->faultstring";
                   error_logs('API', $error_str);
                   if($ivr_debug) IVRdebug($error_str);
                   unset($client);
                   unset($result);
                   return;
               }
               unset($client);
             }
             if($ivr_debug) IVRdebug("Got response:");
             if($ivr_debug) IVRdebug($result);

          }

          # got result here $result;
          # retrun param
          if($soap->return_param) {
                $return_param = $soap->return_param;
                if($ivr_debug) IVRdebug("Define retrun param as $return_param");
                if(isset($result->$return_param)) {
                   $result = $result->$return_param;
                   if($ivr_debug) IVRdebug("Extract result from $return_param");
                   if($ivr_debug) IVRdebug($result);
                }
                else {
                    if($ivr_debug) IVRdebug("Param NOT found in response array/class");
                }
          }
          else {
              if($ivr_debug) IVRdebug("Retrun param not defined");
          }

          # Convert XML/array to class
          if(!is_object($result)) {
              if(is_array($result)) {
                 if($ivr_debug) IVRdebug("Return value is an Array; Converting to Object");
                 $result = (object) $result;
              }
              elseif(is_string($result)) {
                  if(substr($result, 0, 5) == '<?xml') {
                     if($ivr_debug) IVRdebug("Return value is XML; Converting to Object");
                     $result = @simplexml_load_string($result);
                  }
                  elseif(substr($result,0,2) == '[{' || (substr($result,0,1) == '{' && substr($result,-1) == '}')) {
                     if($ivr_debug) IVRdebug("Return value is JSON; Converting to Object");
                     $result = json_decode($result);
                  }

              }
              if($ivr_debug) IVRdebug($result);
          }


          # grab a spacific array defined at the end of the function (separated by ';');
          if($find_array) {
                if($ivr_debug) IVRdebug("Extract result from \"$find_array\"\nValue:");
                $find_array = explode(';', $find_array);
                $filter_value = 0;
                foreach($find_array as $find_ver) {
                   if(strpos($find_ver,'=')) {
                      $filter_value = 1;
                      break;
                   }
                   if(isset($result->$find_ver)) {
                     $result = $result->$find_ver;
                   } else {
                     break;
                   }
                }
                if($ivr_debug) IVRdebug($result);
          }
          # check data type and convert it to array
          if(is_string($result)) {
             $json = 0;
             if(substr($result,0,2) == '[{' || (substr($result,0,1) == '{' && substr($result,-1) == '}')) {
               msg("Converting JSON data");
               $result = json_decode($result);
             } else {
               if(strlen($soap->text_delimiter) == 2) {
                  $line_break = substr($soap->text_delimiter, 0, 1);
                  $field_break = substr($soap->text_delimiter, 1, 1);
                  if($line_break == 'n') $line_break = "\n";
                    elseif($line_break == 'r') $line_break = "\r\n";
               }
               else {
                  $line_break = "\n";
                  $field_break = ',';
               }
               $lines = explode($line_break, $result);
               $row = array();
               if(is_array($lines)) {
                  foreach($lines as $line) {
                     if($line) $row[] = explode($field_break, $line);
                  }
               }
               $result = $row;
             }
          }

          if(!is_array($result))  $result = (array) $result;
          $api_result_array = array();
          if(is_object($result[0]) || is_array($result[0])) {
            foreach($result as $row) {
              $api_result_array[] = obj2array($row, 1);
            }
          } else {
            $api_result_array[] = obj2array($result, 1);
          }

          # Search and compare array to find one record. (CardNo=1234) CardNo={1234}
          if($filter_value) {
             list($key,$val) = explode('=', $find_ver);
             $key = trim($key);
             $val = trim($val);
             if(substr($val,0,1) == '<') {
                 $val = substr($val,1,-1);
                 $val = $UI[$val];
             }

             #find match
             if($ivr_debug && $val) IVRdebug("Filtering result by $key=$val");
             $found = 0;
             foreach($api_result_array AS $i => $row) {
                if(isset($row[$key]) && $row[$key] == $val) {
                     $row['res'] = '1';
                     $found = 1;
                     if($ivr_debug) {
                         IVRdebug("Matched filter on row $i");
                         IVRdebug($row);
                     }
                     break;
                }
             }

             if(!$found) {
                $row = array();
                $row['res'] = '0';
                if($ivr_debug) {
                   IVRdebug("No match found");
                   IVRdebug($row);

                }
             }
             $api_result_array = array();
             $api_result_array[] = $row;
          }

     } else if($conn_method == 'SXML') {

          // SOAP with xml request - Masud
          list($dumy,$conn_name,$function,$find_array) = explode(':',$url);      # WSDL-URL is defined in Dadabase
          $ApiObj->conn_name = $conn_name;
          $soap = db_select("SELECT url,credential,pass_credential,return_param,text_delimiter,submit_header,submit_data,submit_user,submit_pass,return_data ".
                "FROM ivr_api WHERE conn_name='$conn_name' AND active='Y'");
          //msg($soap);
          if($ivr_debug) IVRdebug($soap);
          $WSDL_URL = $soap->url;
          if(!$WSDL_URL) {
              $error_str = "SOAP conn [$conn_name] not defined";
              error_logs('API', $error_str);
              return;
          }

          # Ex: GetCardInfo(BasicCardEnqDataReq->BasicCardEnqDetReq->Card=<CARD>, ID=123)
          # $soap_var = BasicCardEnqDataReq->BasicCardEnqDetReq->Card=<CARD>, ID=123

          $data = $soap->submit_data;
          $unique_seq = 'Gplex_' . time() . rand(1000,9999);
          $data = str_replace('<UNIQUE_SEQ>', $unique_seq, $data);
          $data = str_replace('<API_LOGIN>', $soap->submit_user, $data);
          $data = str_replace('<API_PASS>', $soap->submit_pass, $data);
          $pos = strpos($data, '<24HR_AGO>');
          if ($pos !== false) {
             $data = str_replace('<24HR_AGO>', date("Y-m-d\TH:i:s", time() - 86400) . '+6', $data);
          }
          $pos = strpos($data, '<NOW_L5T>');
          if ($pos !== false) {
             $data = str_replace('<NOW_L5T>', date("YmdHis"), $data);
          }
		  $pos = strpos($data, '<NOW>');
          if ($pos !== false) {
             $data = str_replace('<NOW>', date("Y-m-d\TH:i:s"), $data);
          }
          $pos = strpos($data, '<NOW_TZ>');
          if ($pos !== false) {
             $data = str_replace('<NOW_TZ>', date("Y-m-d\TH:i:s") . '+6', $data);
          }
          $pos = strpos($data, '<48HR_AGO>');
          if ($pos !== false) {
             $data = str_replace('<48HR_AGO>', date("YmdHis", time() - 172800), $data);
          }
		  $pos = strpos($data, '<UNIQUE_SEQ_NUM>');
          if ($pos !== false) {
             $data = str_replace('<UNIQUE_SEQ_NUM>', time() . rand(1000,9999), $data);
          }		  

          $fixed_vars = explode(",", $function);
          if (is_array($fixed_vars) && count($fixed_vars) > 0) {
             foreach ($fixed_vars as $fixed_var) {
                list($key, $val) = explode("=", $fixed_var);
                if (!empty($key) && is_string($key)) {
                   $UI[$key] = $val;
                }
             }
          }

          if (isset($UI['CLI'])) {
             $aCLI = $UI['CLI'];
             $pos = strpos($data . $soap->url, "<NZ_CLI>");
             if ($pos !== false) {
               $nz_cli = $aCLI;
               if (substr($nz_cli, 0, 3) == '880') {
                  $nz_cli = substr($nz_cli, 3);
               } else if (substr($nz_cli, 0, 1) == '0') {
                  $nz_cli = substr($nz_cli, 1);
               }
               $soap->url = str_replace("<NZ_CLI>", $nz_cli, $soap->url);
               $data = str_replace("<NZ_CLI>", $nz_cli, $data);
          }

          $pos = strpos($data . $soap->url, "<FL_CLI>");
          if ($pos !== false) {
             $fl_cli = $aCLI;
             if (substr($fl_cli, 0, 1) == '0') {
                $fl_cli = '88' . $fl_cli;
             } else if (substr($fl_cli, 0, 2) != '88') {
                $fl_cli = '880' . $fl_cli;
             }
             $soap->url = str_replace("<FL_CLI>", $fl_cli, $soap->url);
             $data = str_replace("<FL_CLI>", $fl_cli, $data);
          }

          $pos = strpos($data . $soap->url, "<LZ_CLI>");
          if ($pos !== false) {
             $lz_cli = $aCLI;
             if (substr($lz_cli, 0, 3) == '880') {
                $lz_cli = substr($lz_cli, 2);
             } else if (substr($lz_cli, 0, 1) != '0') {
                $lz_cli = '0' . $lz_cli;
             }
             $soap->url = str_replace("<LZ_CLI>", $lz_cli, $soap->url);
             $data = str_replace("<LZ_CLI>", $lz_cli, $data);
          }

          }

          if (isset($UI) && is_array($UI)) {
             foreach ($UI as $key => $val) {
                $data = str_replace('<'.$key.'>', $val, $data);
             }
          }

             if($ivr_debug) IVRdebug($data);

             if($data) {
                // if ($soap->pass_credential=='B') $header[] = 'Authorization: Basic ' . base64_encode($soap->submit_user.':'.$soap->submit_pass);
                if (!empty($soap->submit_header)) {
                   $header = array();
                   $fixed_vars = explode(",", $soap->submit_header);
                   if (is_array($fixed_vars) && count($fixed_vars) > 0) {
                      foreach ($fixed_vars as $fixed_var) {
                         if (!empty($fixed_var) && is_string($fixed_var)) {
                            $header[] = $fixed_var;
                         }
                      }
                   }
                } else {
                   $header = array(
                           "Content-type: text/xml;charset=\"utf-8\"",
                           "Accept: text/xml",
                           "Cache-Control: no-cache",
                           "Pragma: no-cache",
                           "SOAPAction: \"run\""
                   );
                }
                if ($soap->pass_credential=='B') $header[] = 'Authorization: Basic ' . base64_encode($soap->submit_user.':'.$soap->submit_pass);

                $header[] = "Content-length: ".strlen($data);
//echo '$soap->url='. $soap->url;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $soap->url );
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_TIMEOUT,        20);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_POST,           true );
                curl_setopt($ch, CURLOPT_INTERFACE, $listening_ip);
                curl_setopt($ch, CURLOPT_POSTFIELDS,     $data);
                curl_setopt($ch, CURLOPT_HTTPHEADER,     $header);
                $result = @curl_exec($ch);
                if ($result === false) {
                        $ApiObj->stop_ts = time();
                        $ApiObj->total_time = $ApiObj->stop_ts - $ApiObj->start_ts;
                        $ApiObj->response_code = curl_errno($ch);
                        $error_str = "$conn_name: " . curl_error($ch);
                        curl_close($ch);
//                        error_logs('API', $error_str);
                        unset($result);
                        do_api_log($ApiObj);
                        return;
                } else {
                        $header_info = curl_getinfo($ch);
                        $ApiObj->total_time = $header_info['total_time'];
                        $ApiObj->response_code = $header_info['http_code'];
                        $ApiObj->transfer_time = $header_info['starttransfer_time'];
                        $ApiObj->dl_size = $header_info['size_download'];
                        $ApiObj->download_speed = $header_info['speed_download'];
                        $ApiObj->upload_speed = $header_info['speed_upload'];
                }
                curl_close($ch);

                $isXML = true;
                $isJSON = false;
                if(substr($result,0,2) == '[{' || (substr($result,0,1) == '{' && substr($result,-1) == '}')) {
                   $isJSON = true;
                   $isXML = false;
                   if($ivr_debug) IVRdebug("Return value is JSON; Converting to Object");
                   $result = json_decode($result);
                }

               if ($isXML) {
                $result = str_replace(array("-", ":"), "", $result);
                $result = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $result);

                if($ivr_debug) IVRdebug("Got response ($soap->url): header:");

                if($ivr_debug) IVRdebug($header);
                if($ivr_debug) IVRdebug($result);


                if (!empty($soap->return_data)) {
                   $grep_data = '<data>';
                   $return_tags = explode(",", $soap->return_data);
                   if (is_array($return_tags)) {
                      foreach ($return_tags as $tagname) {
                         $rd_tag_pos = strpos($result, $tagname);
                         if ($rd_tag_pos !== false) {
                            preg_match('/<'.$tagname.'(\s+[^>]*)?>(.*?)<\/'.$tagname.'>/si', $result, $matches);
                            $grep_data .= "<$tagname>$matches[2]</$tagname>";
                         }
                      }
                   }
                   $grep_data .= '</data>';
                   $result = $grep_data;
                }
                $result = simplexml_load_string($result);
               }
             }
             if($ivr_debug) IVRdebug("Got response:");
             if($ivr_debug) IVRdebug($result);

          # got result here $result;
          # retrun param
          if($soap->return_param) {
                $return_param = $soap->return_param;
                if($ivr_debug) IVRdebug("Define retrun param as $return_param");
                if(isset($result->$return_param)) {
                   $result = $result->$return_param;
                   if($ivr_debug) IVRdebug("Extract result from $return_param");
                   if($ivr_debug) IVRdebug($result);
                }
                else {
                    if($ivr_debug) IVRdebug("Param NOT found in response array/class");
                }
          }
          else {
              if($ivr_debug) IVRdebug("Retrun param not defined");
          }

          # Convert XML/array to class
          if(!is_object($result)) {
              if(is_array($result)) {
                 if($ivr_debug) IVRdebug("Return value is an Array; Converting to Object");
                 $result = (object) $result;
              }
              elseif(is_string($result)) {
                  if(substr($result, 0, 5) == '<?xml') {
                     if($ivr_debug) IVRdebug("Return value is XML; Converting to Object");
                     $result = @simplexml_load_string($result);
                  }
                  elseif(substr($result,0,2) == '[{' || (substr($result,0,1) == '{' && substr($result,-1) == '}')) {
                     if($ivr_debug) IVRdebug("Return value is JSON; Converting to Object");
                     $result = json_decode($result);
                  }

              }
              if($ivr_debug) IVRdebug($result);
          }


          # grab a spacific array defined at the end of the function (separated by ';');
          if($find_array) {
                if($ivr_debug) IVRdebug("Extract result from \"$find_array\"\nValue:");
                $find_array = explode(';', $find_array);
                $filter_value = 0;
                /*
                PHP Bug could not get XML aprsed array from object
                */
                $result = json_encode($result);
                $result = json_decode($result);
                foreach($find_array as $find_ver) {
                   if(strpos($find_ver,'=')) {
                      $filter_value = 1;
                      break;
                   }
                   if(isset($result->$find_ver)) {
                     $result = $result->$find_ver;
                   } else {
                     break;
                   }
                }
                if($ivr_debug) IVRdebug($result);
          }
          # check data type and convert it to array
          if(is_string($result)) {
             $json = 0;
             if(substr($result,0,2) == '[{' || (substr($result,0,1) == '{' && substr($result,-1) == '}')) {
               msg("Converting JSON data");
               $result = json_decode($result);
             } else {
               if(strlen($soap->text_delimiter) == 2) {
                  $line_break = substr($soap->text_delimiter, 0, 1);
                  $field_break = substr($soap->text_delimiter, 1, 1);
                  if($line_break == 'n') $line_break = "\n";
                    elseif($line_break == 'r') $line_break = "\r\n";
               }
               else {
                  $line_break = "\n";
                  $field_break = ',';
               }
               $lines = explode($line_break, $result);
               $row = array();
               if(is_array($lines)) {
                  foreach($lines as $line) {
                     if($line) $row[] = explode($field_break, $line);
                  }
               }
               $result = $row;
             }
          }

          if(!is_array($result))  $result = (array) $result;
          $api_result_array = array();
          if(is_object($result[0]) || is_array($result[0])) {
            foreach($result as $row) {
              $api_result_array[] = obj2array($row, 1);
            }
          } else {
            //$api_result_array[] = obj2array($result, 1);
            $api_result_array[] = json_decode(json_encode($result), true);
          }

          # Search and compare array to find one record. (CardNo=1234) CardNo={1234}
          if($filter_value) {
             list($key,$val) = explode('=', $find_ver);
             $key = trim($key);
             $val = trim($val);
             if(substr($val,0,1) == '<') {
                 $val = substr($val,1,-1);
                 $val = $UI[$val];
             }

             #find match
             if($ivr_debug && $val) IVRdebug("Filtering result by $key=$val");
             $found = 0;
             foreach($api_result_array AS $i => $row) {
                if(isset($row[$key]) && $row[$key] == $val) {
                     $row['res'] = '1';
                     $found = 1;
                     if($ivr_debug) {
                         IVRdebug("Matched filter on row $i");
                         IVRdebug($row);
                     }
                     break;
                }
             }

             if(!$found) {
                $row = array();
                $row['res'] = '0';
                if($ivr_debug) {
                   IVRdebug("No match found");
                   IVRdebug($row);

                }
             }
             $api_result_array = array();
             $api_result_array[] = $row;
          }
     }


     else if($conn_method == 'XSQL') {
       # SQL query in Local DB
          list($dumy,$query_type,$query_text) = explode(':',$url);
          # query_type: SDF=System Define Function; SSF=Simple SQL Function
          $query_type = strtoupper($query_type);
          $ApiObj->conn_name = $query_type;

          if($query_type == 'SDF') {
            list($fn,$param) = explode('(', $query_text);
            $ApiObj->api_function = $fn;
            $fn = 'SDF_' . $fn;
            if(function_exists($fn)) {
               $param = substr($param, 0, -1);

               if(strlen($param) > 0) {
                   $param = explode(',', $param);
               }

               $api_result_array[] = $fn($param);

               if($ivr_debug) IVRdebug("Calling XSQL/$query_type function $fn($param)");
               if($ivr_debug) IVRdebug("Got response:");
               if($ivr_debug) IVRdebug($api_result_array);

            } else {
               msg("SDF function not found");
               if($ivr_debug) IVRdebug("Invalid function; $query_type function $fn");
            }
          }
          else if($query_type == 'SSF') {
              #CURDATE(),SUBSTR
              if(strlen($query_text) > 0) {
                $result = db_select("SELECT $query_text LIMIT 1");
                if($ivr_debug) IVRdebug("Calling XSQL/$query_type SELECT $query_text");
                if($ivr_debug) IVRdebug("Got response:");
                if($ivr_debug) IVRdebug($result);
              }
              $api_result_array[] = (array) $result;
          }
          else {
            msg("Not a valid QueryType: $query_type");
            if($ivr_debug) IVRdebug("Invalid Type of Query; $query_type");
          }
     } else {
        msg("Method $conn_method not found.");
        if($ivr_debug) IVRdebug("Invalid Method; $conn_method");
     }

     if($ivr_debug) IVRdebug("Final data for IVR; converted to Array with both index & key");
     if($ivr_debug) IVRdebug($api_result_array);
     if($conn_method != 'XSQL') do_api_log($ApiObj);

     if($conn_method == 'SXML' && $conn_name == 'L5TRANS') {
        if (count($api_result_array) > 5) {
           $api_result_array = array_slice($api_result_array, 0, 5);
        }
     }

     return $api_result_array;
}

