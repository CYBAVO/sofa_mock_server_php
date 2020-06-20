<?php
// Copyright (c) 2018-2020 The CYBAVO developers
// All Rights Reserved.
// NOTICE: All information contained herein is, and remains
// the property of CYBAVO and its suppliers,
// if any. The intellectual and technical concepts contained
// herein are proprietary to CYBAVO
// Dissemination of this information or reproduction of this materia
// is strictly forbidden unless prior written permission is obtained
// from CYBAVO.

include_once 'helper/apicaller.php';
include_once 'models/apicode.php';
include_once 'mockserver.conf.php';

date_default_timezone_set('UTC');
init_db();

function log_access($uri, $resp) {
    file_put_contents('php://stdout', sprintf('[%s] %s %s %d', date('D M j H:i:s Y'),
        $uri, json_encode(json_decode($resp['result'])), $resp['status'])."\n");
}

function log_text($uri, $text) {
    file_put_contents('php://stdout', sprintf('[%s] %s %s', date('D M j H:i:s Y'), $uri, $text)."\n");
}

function get_query_params() {
    $params = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
    return explode('&', $params);
}

function response($resp)
{
    header_remove();
    http_response_code($resp['status']);
    header('Content-Type: application/json');
    return json_encode(json_decode($resp['result']));
}

function response_plaintext($status_code, $text)
{
    header_remove();
    http_response_code($status_code);
    return $text;
}

function base64url_encode($data) {
    return strtr(base64_encode($data), '+/', '-_');
} 

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$post_data = file_get_contents('php://input');
$query = get_query_params();

if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/apitoken$/i', $path, $m)) {
    $payload = json_decode($post_data, true);
    set_api_code($m['wallet_id'], $payload['api_code'], $payload['api_secret']);
    $resp['status'] = 200;
    $resp['result'] = '{"result": 1}';
    log_access($m['0'], $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/addresses$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/addresses';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/pooladdress$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/pooladdress';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/callback\/resend$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/collection/notifications/manual';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/withdraw$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/sender/transactions';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/sender\/transactions\/(?<order_id>[\w\-_]+)\/cancel$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/sender/transactions/'.$m['order_id'].'/cancel';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/sender\/transactions\/(?<order_id>[\w\-_]+)$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/sender/transactions/'.$m['order_id'];
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/sender\/balance$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/sender/balance';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/apisecret$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/apisecret';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/notifications$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/notifications';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/notifications\/get_by_id$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/notifications/get_by_id';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/receiver\/notifications\/txid\/(?<txid>[\w\-_]+)\/(?<vout_index>\d+)$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/receiver/notifications/txid/'.$m['txid'].'/'.$m['vout_index'];
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/sender\/notifications\/order_id\/(?<order_id>[\w\-_]+)$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/sender/notifications/order_id/'.$m['order_id'].'/'.$m['vout_index'];
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/transactions$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/transactions';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/blocks$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/blocks';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/addresses\/invalid-deposit$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/addresses/invalid-deposit';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/info$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/info';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/addresses\/verify$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/addresses/verify';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (preg_match('/\/v1\/mock\/wallets\/(?<wallet_id>\d+)\/autofee$/i', $path, $m)) {
    $uri = '/v1/sofa/wallets/'.$m['wallet_id'].'/autofee';
    $resp = make_request($m['wallet_id'], $method, $uri, $query, $post_data);
    log_access($uri, $resp);
    echo response($resp);
    return;
} else if (!strcmp($path, '/v1/mock/wallets/callback')) {
    $callback = json_decode($post_data, true);
    $ac = get_api_code($callback['wallet_id']);

    $header_checksum = $_SERVER['HTTP_X_CHECKSUM'];
    $payload = $post_data.$ac['api_secret'];
    $checksum = base64url_encode(hash('sha256', $payload, true));
    log_text('/v1/mock/callback',
        "header_checksum: ".$header_checksum." <-> checksum: ".$checksum."\npayload: ".$post_data);
    if (!strcmp($header_checksum, $checksum)) {
        // Callback Type
        // DepositCallback  = 1
        // WithdrawCallback = 2
        // CollectCallback  = 3
        // AirdropCallback  = 4

        // Processing State
        // ProcessingStateInPool  = 0
        // ProcessingStateInChain = 1
        // ProcessingStateDone    = 2

        // Callback State
        // CallbackStateInit            = 0
        // CallbackStateHolding         = 1
        // CallbackStateInPool          = 2
        // CallbackStateInChain         = 3
        // CallbackStateDone            = 4
        // CallbackStateFailed          = 5
        // CallbackStateResended        = 6
        // CallbackStateRiskControl     = 7
        // CallbackStateCancelled       = 8
        // CallbackStateUTXOUnavailable = 9
        // CallbackStateDropped         = 10
        // CallbackStateInChainFailed   = 11
        // CallbackStatePaused          = 12

        $callback = json_decode($post_data, true);
        if ($callback['type'] == 1) { // DepositCallback
            //
            // deposit unique ID
            $uniqueID = $callback['txid'].'_'.$callback['vout_index'];
            //
            if ($callback['processing_state'] == 2) { // ProcessingStateDone
                // deposit succeeded, use the deposit unique ID to update your business logic
            }
        } else if ($callback['type'] == 2) { // WithdrawCallback
            //
            // withdrawal unique ID
            $uniqueID = $callback['order_id'];
            //
            if ($callback['state'] == 3 && $callback['processing_state'] == 2) { // CallbackStateInChain && ProcessingStateDone
                // withdrawal succeeded, use the withdrawal uniqueID to update your business logic
            } else if ($callback['state'] == 5 || $callback['state'] == 11) { // CallbackStateFailed || CallbackStateInChainFailed
                // withdrawal failed, use the withdrawal unique ID to update your business logic
            }
        } else if ($callback['type'] == 4) { // AirdropCallback
            //
            // airdrop unique ID
            $uniqueID = $callback['txid'].'_'.$callback['vout_index'];
            //
            if ($callback['processing_state'] == 2) { // ProcessingStateDone
                // airdrop succeeded, use the airdrop unique ID to update your business logic
            }
        }
        // reply 200 OK to confirm the callback has been processed
        echo response_plaintext(200, 'OK');
    } else {
        echo response_plaintext(400, 'Bad checksum');
    }
    return;
} else if (!strcmp($path, '/v1/mock/wallets/withdrawal/callback')) {
    $callback = json_decode($post_data, true);

    // How to verify:
    // 1. Try to find corresponding API secret by $callback['order_id']
    // 2. Calculate checksum then compare to X-CHECKSUM header (refer to sample code bellow)
    // 3. If these two checksums match and the request is valid in your system,
    //    reply 200, OK otherwise reply 400 to decline the withdrawal

    // sample code to calculate checksum and verify
    // $header_checksum = $_SERVER['HTTP_X_CHECKSUM'];
    // $payload = $post_data.$ac['api_secret'];
    // $checksum = base64url_encode(hash('sha256', $payload, true));
    // if (strcmp($header_checksum, $checksum) != 0) {
    //     echo response_plaintext(400, 'Bad checksum');
    //     return;
    // }

    // reply 200 OK to confirm the callback has been processed
    echo response_plaintext(200, 'OK');
    return;
}

$resp['status'] = 404;
$resp['result'] = '{"result": "invalid path"}';
log_access($path, $resp);
echo response($resp);

?>

