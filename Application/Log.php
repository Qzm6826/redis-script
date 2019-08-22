<?php

namespace App;

class Log{
    /**
     * 打日志，支持SAE环境
     * @param string $msg 日志内容
     * @param string $level 日志等级
     * @param bool $wf 是否为错误日志
     */
    public static function write($msg, $level='DEBUG', $wf=false){
        $msg = date('[ Y-m-d H:i:s ]')."[{$level}]".$msg."\r\n";
        $logPath = __DIR__ . '/Log/'.date('Ymd').'.log';
        if($wf){
            $logPath .= '.wf';
        }
        file_put_contents($logPath, $msg, FILE_APPEND);
    }

    /**
     * 打印fatal日志
     * @param string $msg 日志信息
     */
    public static function fatal($msg){
        self::write($msg, 'FATAL', true);
    }

    /**
     * 打印warning日志
     * @param string $msg 日志信息
     */
    public static function warn($msg){
        self::write($msg, 'WARN', true);
    }

    /**
     * 打印notice日志
     * @param string $msg 日志信息
     */
    public static function notice($msg){
        self::write($msg, 'NOTICE');
    }

    /**
     * 打印debug日志
     * @param string $msg 日志信息
     */
    public static function debug($msg){
        self::write($msg, 'DEBUG');
    }

    /**
     * 打印sql日志
     * @param string $msg 日志信息
     */
    public static function sql($msg){
        self::write($msg, 'SQL');
    }
}
