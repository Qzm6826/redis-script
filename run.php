<?php
require __DIR__ . "/vendor/autoload.php";
use App\Index;
use App\Config;
set_time_limit(0);

$class = new Index();

function checkParams($params)
{
    if (
        empty($params) ||
        !is_array($params)
    ){
        return false;
    }
    return true;
}

if (checkParams(Config::$HKeys) ){
    foreach (Config::$HKeys as $val) {
        $class->iterator = null;
        if(!$class->processHash($val)){
            continue;
        }else{
            break;
        }
    }
}

if (checkParams(Config::$Keys)){
    foreach (Config::$Keys as $val) {
        $class->iterator = null;
        if(!$class->processKey($val)){
            continue;
        }else{
            break;
        }
    }
}

$class->close();

exit();
