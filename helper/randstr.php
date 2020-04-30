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

function random_string($length = 8) {
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charsetLen = strlen($charset);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $r .= $charset[rand(0, $charsetLen - 1)];
    }
    return $r;
}
?>