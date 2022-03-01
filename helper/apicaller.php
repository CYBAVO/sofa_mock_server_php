<?php
// Copyright (c) 2018-2022 The CYBAVO developers
// All Rights Reserved.
// NOTICE: All information contained herein is, and remains
// the property of CYBAVO and its suppliers,
// if any. The intellectual and technical concepts contained
// herein are proprietary to CYBAVO
// Dissemination of this information or reproduction of this materia
// is strictly forbidden unless prior written permission is obtained
// from CYBAVO.

include_once(dirname(__DIR__).'/mockserver.conf.php');
include_once(dirname(__DIR__).'/models/apicode.php');
include_once 'randstr.php';

function build_checksum($params, $secret, $t, $r, $postData) {
    array_push($params, 't='.$t, 'r='.$r);
    if (!empty($postData)) {
        array_push($params, $postData);
    }
    sort($params);
    array_push($params, 'secret='.$secret);
    return hash('sha256', implode('&', $params));
}

function make_request($targetID, $method, $api, $params, $postData) {
    $r = random_string();
    $t = time();
    $url = $GLOBALS['api_server_url'].$api.'?t='.$t.'&r='.$r;
    if (!empty($params)) {
        $url .= '&'.implode('&', $params);
    }

    $ch = curl_init();
    $ac = get_api_code($targetID);
    if (empty($ac)) {
        // try read-only API code
        $ac = get_api_code(0);
    }
    $header = array(
        'X-API-CODE: '.$ac['api_code'],
        'X-CHECKSUM: '.build_checksum($params, $ac['api_secret'], $t, $r, $postData),
        'User-Agent: php',
        'Content-Type: application/json',
    );
    curl_setopt($ch, CURLOPT_URL, $url);
    if (!strcasecmp($method, 'POST') || !strcasecmp($method, 'DELETE')) {
        if (!strcasecmp($method, 'POST')) {
            curl_setopt($ch, CURLOPT_POST, true);
        } else {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        if (!is_null($postData) && strlen($postData) > 0) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
            array_push($header, 'Content-Length: '.strlen($postData));
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    $result = curl_exec($ch);
    $resp = array();

    $headerSize = curl_getinfo($ch , CURLINFO_HEADER_SIZE);
    $headerStr = substr($result, 0, $headerSize);
    $bodyStr = substr($result, $headerSize);
    $headers = headersToArray($headerStr);

    $resp['result'] = $bodyStr;
    $resp['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    //
    // verify checksum of a successful response
    //
    if ($resp['status'] == 200) {
        $header_checksum = $headers['X-Checksum'];
        $payload = $bodyStr.$ac['api_secret'];
        $checksum = base64url_encode(hash('sha256', $payload, true));
        if (strcmp($header_checksum, $checksum) != 0) {
            $resp['result'] = 'mismatched response checksum';
            $resp['status'] = 400;
        }
    }
    curl_close($ch);

    return $resp;
}

function headersToArray($str)
{
    $headers = array();
    $tmp = explode("\r\n" , $str);
    for ($i = 0; $i < count($tmp) ; ++$i)
    {
        $pos = strpos($tmp[$i], ":");
        if ($pos != false)
        {
            $headerName = substr($tmp[$i], 0, $pos);
            $headerValue = substr($tmp[$i], $pos + 2);
            $headers[$headerName] = $headerValue;
        }
    }
    return $headers;
}

?>