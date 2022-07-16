<?php
function getLanguage()
{
    if (isset($_SESSION['language'])) {
        return $_SESSION['language'];
    }
    return null;
}

function setLanguage($param_object)
{
    $btn_value = $param_object->btn_value;
    if ($param_object->btn_value == null) return;
    $_SESSION['language'] = $btn_value;
}

function getAccountBalance($param_object, $page_element)
{
    $balance = getUserAccountBalance($param_object->cli);
    $language = getLanguage();
    if ($language == null || $language == "EN") {
        $displaying_text = $page_element->display_name_en;
    } else {
        $displaying_text = $page_element->display_name_bn;
    }
    return str_replace("##", $balance, $displaying_text);
}

function getDateTime($timestamp)
{
    if ($timestamp == null) return '';
    $format = "d/m/Y h:i:s";
    $time = date($format, strtotime("$timestamp"));
    return $time;
}

function formatBytes($bytes, $precision = 2)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}


//read balance

function expand_num($num)
{
    if ($num > 99) {
        if ($num > 999) {
            $T = floor($num / 1000);
            $num -= $T * 1000;
        }
        if ($num > 99) {
            $H = floor($num / 100);
            $num -= $H * 100;
        }
    }
    $prompt = '';
    $N = $num;
    if ($T) $prompt .= $T . ',thousand,';
    if ($H) $prompt .= $H . ',hundred,';
    if ($N) $prompt .= $N . ',';
    return $prompt;
}

function read_amount($balance, $param)
{
    $bug = 'dollar';
    $cent = 'cent';
    #$balance = str_replace(',', '', $balance);
    $balance = $balance + 0;
    $balance = sprintf("%.02f", $balance);

    list($num, $dec) = explode('.', $balance);
    $param = explode(',', strtoupper($param));

    # multi-currency file; dollar, dollar1, dollar2 etc
    if (strlen($param[0]) == 2) {
        $dollar_suffix = substr($param[0], 1);
        $bug .= $dollar_suffix;
        $cent .= $dollar_suffix;
    }
    if ($balance < 0) {
        if (in_array('SIGNED', $param)) $negetive = 1;
        $num *= -1;
    }
    if ($num > 1) $bug .= 's';

    if (in_array('MILLION', $param)) {
        # 1,000,000,000,000
        if ($num > 999999999) {
            $B = floor($num / 1000000000);
            $num -= $B * 1000000000;
        }
        if ($num > 999999) {
            $M = floor($num / 1000000);
            $num -= $M * 1000000;
        }
    } else {
        # 1000,00,00,00,000
        if ($num > 9999999) {
            $C = floor($num / 10000000);
            $num -= $C * 10000000;
        }
        if ($num > 99999) {
            $L = floor($num / 100000);
            $num -= $L * 100000;
        }
    }

    if ($num > 999) {
        $T = floor($num / 1000);
        $num -= $T * 1000;
    }
    $N = $num;

    $prompt = '';

    if ($B) $prompt .= expand_num($B) . 'billion,';
    if ($M) $prompt .= expand_num($M) . 'million,';
    if ($C) $prompt .= expand_num($C) . 'crore,';
    if ($L) $prompt .= $L . ',lac,';
    if ($T) $prompt .= expand_num($T) . 'thousand,';
    if ($N) $prompt .= expand_num($N);
    if (!$prompt) $prompt = '0,';
    if ($negetive) $prompt = 'negetive,' . $prompt;

    if (in_array('POINT', $param)) {
        if ($dec > 0) {
            $prompt .= 'point,' . substr($dec, 0, 1) . ',' . substr($dec, -1) . ',';
        }
        $prompt .= $bug;
    } else {
        $prompt .= $bug;
        $dec = $dec + 0;
        if ($dec > 0) {
            if ($dec > 1) $cent .= 's';
            $prompt .= ',and,' . $dec . ',' . $cent;
        }
    }

    return $prompt;
}

function read_value($value = '', $param = '')
{

    $value = explode(' ', trim($value));
    $multi_prompts = '';
    foreach ($value as $var) {
        $prompt = '';
        $var = trim($var);

        $var = str_replace(',', '', $var);
        if (strlen($var) == 0) $var = 0;
        if (is_numeric($var)) {
            $data_type = substr($param, 0, 1);
            if ($data_type == '$') $prompt = read_amount($var, $param);
//            elseif ($data_type == 'Y') $prompt = read_date($var, $param);
//            elseif ($data_type == 'D') $prompt = read_data_pack($var, $param);
//            else $prompt = read_number($var, $param);
        } else {
//            if (strpos($var, ':')) $prompt = read_time($var, $param);
//            else $prompt = read_date($var, $param);
        }
        if ($prompt != '') {
            if ($multi_prompts != '') $multi_prompts .= ',blank1sec,';
            $multi_prompts .= $prompt;
        }
    }

    return $multi_prompts;
}


