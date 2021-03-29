<?php
// Copyright (c) 2018-2021 The CYBAVO developers
// All Rights Reserved.
// NOTICE: All information contained herein is, and remains
// the property of CYBAVO and its suppliers,
// if any. The intellectual and technical concepts contained
// herein are proprietary to CYBAVO
// Dissemination of this information or reproduction of this materia
// is strictly forbidden unless prior written permission is obtained
// from CYBAVO.

class MyDB extends SQLite3
{
    function __construct() {
        $this->open('./runtime/mocksrv.db');
    }
}

function init_db() {
    if (!file_exists('./runtime')) {
        mkdir('./runtime');
    }
    $db = new MyDB();
    $db->exec('CREATE TABLE IF NOT EXISTS mock_apicode
    (api_code_id integer,
      api_code varchar(255),
      api_secret varchar(255),
      wallet_id integer UNIQUE,
    PRIMARY KEY (api_code_id))');
    $db->close();
}

function get_api_code($wallet_id) {
    $db = new MyDB();
    $stmt = $db->prepare('SELECT api_code, api_secret FROM mock_apicode WHERE wallet_id=:w');
    $stmt->bindValue(':w', $wallet_id, SQLITE3_INTEGER);
    $rs = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
    $db->close();
    return $rs;
}

function set_api_code($wallet_id, $api_code, $api_secret) {
    $db = new MyDB();
    $stmt = $db->prepare('REPLACE INTO mock_apicode (wallet_id, api_code, api_secret) VALUES(:w, :c, :s)');
    $stmt->bindValue(':w', $wallet_id, SQLITE3_INTEGER);
    $stmt->bindValue(':c', $api_code, SQLITE3_TEXT);
    $stmt->bindValue(':s', $api_secret, SQLITE3_TEXT);
    $stmt->execute();
    $db->close();
}

?>