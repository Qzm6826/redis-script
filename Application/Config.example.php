<?php

namespace App;

class Config{

    static $Redis = array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'db' => 8
    );

    static $HKeys = array(
        'hall1',
        "hall2"
    );

    static $Keys = array(
        'hall3',
        'str'
    );

}