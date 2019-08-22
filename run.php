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
    $hCount = 60;
    for($i = 0; $i < $hCount; $i++){
        foreach (Config::$HKeys as $val) {
            if(!$class->processHash($val)){
                continue;
            }else{
                break;
            }
        }
        $hCount--;
        sleep(1);
    }
}

if (checkParams(Config::$Keys)){
    $sCount = 60;
    for ($i = 0; $i < $sCount; $i++) {
        foreach (Config::$Keys as $val) {
            if(!$class->processKey($val)){
                continue;
            }else{
                break;
            }
        }
        $sCount--;
        sleep(1);
    }
}

$class->close();

exit();
