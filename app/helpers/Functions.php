<?php

function check_session() {

    return isset($_SESSION['user']);
}

function printData($data, $die = true)
{
    echo '<pre>';

    if (is_object($data) || is_array($data)) {
        print_r($data);
    } else {
        echo $data;
    }

    if($die) {
        die('<br>FIM<br>');
    }
}
