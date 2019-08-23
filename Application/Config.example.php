<?php

namespace App;

class Config{

    static $Redis = array(
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'db' => 8
    );

    /**
     * HKeys : 哈希key数组
     * @var array
     */
    static $HKeys = array(
        'hall1',
        "hall2"
    );

    /**
     * Keys : 字符串key前缀，哈希key
     * @var array
     */
    static $Keys = array(
        'hall3',
        'str'
    );

}