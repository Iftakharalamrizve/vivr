<?php
//<!-----

//Set source IP for API call
// $listening_ip = '10.101.92.20';
$listening_ip = '198.74.96.247';
// $listening_ip = '192.168.11.53';

global $mysqli;
global $mysqli_error;

function db_conn($db_suffix = '') {
global $g, $mysqli;
$db_host = '98.74.96.247';
// $db_host = '192.168.11.53';
$db_user = 'Rizve@123';
// $db_user = 'gplex';
$db_pass = 'Rizve@123';
// $db_pass = 'gplex';
// $db = 'cc';
$db = 'cc';
$mysqli = new mysqli("$db_host","$db_user","$db_pass");
if (!$mysqli) {
  echo "Not Connnected";
  msg("Can't connect to MySQL!");
  return 1;
}

$mysqli->select_db($db);
}
db_conn();

function mysql_keep_alive() {
global $mysqli;
  if($mysqli->ping()!=1 && $mysqli->ping()!=1) {
      @$mysqli->close();
      while(db_conn()==1) sleep(5);
  }
}

function db_update($sql) {
   
   
   global $mysqli, $mysqli_error;
   msg($sql);
   $mysqli_error = '';
   if (!$mysqli->query($sql)) {
      $mysqli_error = $mysqli->error;
   }
   return $mysqli->affected_rows;
}

function db_select($sql) {
global $mysqli;
   msg($sql);
   @$result = $mysqli->query($sql);
   if($mysqli->affected_rows == 1) {
      $row = $result->fetch_object();
      $mysqli->next_result();
   }
   if(is_object($result)) $result->close();
   if(is_object($row)) return $row;
      else return 0;
}

function db_select_one($sql) {
global $mysqli;
   msg($sql);
   $data = NULL;
   @$result = $mysqli->query($sql);
   if($mysqli->affected_rows == 1) {
      $row = $result->fetch_array(2);
      $data = $row[0];
   }
   if(is_object($result)) $result->close();
   return $data;
}


function db_select_array($sql,$i=0) {
global $mysqli;
   msg($sql);
   $result = $mysqli->query($sql);
   if($mysqli->affected_rows > 0) {
      while($row = $result->fetch_object()) {
        if($i == 0) {
            $key = current($row);   # first row should be unique
            $obj[$key] = $row;
        } else {
            if($i == 1) {
                $obj[] = $row;
            } else {
                # i = 2
                if(!true) $field = key($row);
                $obj[] = $row->$field;
            }
        }
      }
   } else $obj = 0;
   if(is_object($result)) $result->close();
   return $obj;
}


function msg($str) {
  global $debug,$agi,$logfile;
  if($debug) {
      if(is_array($str) || is_object($str)) $str = print_r($str, true);
  }

  if($debug == 1) echo "$str\n";
    elseif($debug == 2) $agi->verbose($str);
    elseif($debug == 3) file_put_contents($logfile, "$str\n", FILE_APPEND | LOCK_EX);
}
//------>

