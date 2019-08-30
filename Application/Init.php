<?php

namespace App;

class Init{

    private $redis = null;
    public $iterator = null;
    private $host = null;
    private $pwd = null;
    private $db = 0;
    private $port = 6379;

    public function __construct()
    {
        $this->host = !empty(Config::$Redis['host']) ? Config::$Redis['host'] : '127.0.0.1';
        $this->pwd = !empty(Config::$Redis['password']) ? Config::$Redis['password'] : null;
        $this->db = !empty(Config::$Redis['db']) ? Config::$Redis['db'] : 0;
        $this->port = !empty(Config::$Redis['port']) ? Config::$Redis['port'] : 6379;
        if (!$this->redis){
            $this->MyRedis();
        }
    }

    private function MyRedis()
    {
        $this->redis = new \Redis();
        $this->redis->connect($this->host, $this->port);
        if (!empty($this->pwd)){
            $this->redis->auth($this->pwd);
        }
        $this->redis->select($this->db);
        $this->redis->setOption(\Redis::OPT_SCAN,\Redis::SCAN_RETRY);
    }

    public function getType($key)
    {
        return $this->redis->type($key);
    }

    public function getKeyLength($key, $type = "hash")
    {
        $keyLen = 0;
        if ($type == "hash"){
            $keyLen = $this->redis->hLen($key);
        }elseif ($type == "str"){
            $keyLen = $this->redis->strlen($key);
        }
        return $keyLen;
    }

    public function getHashData($hKey)
    {
        $count = 0;
        $data = array();
        while ($array = $this->redis->hScan($hKey, $this->iterator, "*", 6500)){
            if ($count >= 60 || !$array) {
                break;
            }
            $data = array_merge($data, array_keys($array));
            $count++;
        }
        return $data;
    }

    public function getRedisData($key)
    {
        $count = 0;
        $data = array();
        while ($array = $this->redis->scan($this->iterator, $key . "*", 6500)){
            if ($count >= 60 || !$array) {
                break;
            }
            $data = array_merge($data, $array);
            $count++;
        }
        return $data;
    }

    public function hDel($key, $hKeys)
    {
        $hKeys = implode(" ", $hKeys);
        $cmd = "cd " . __DIR__ . "/Script/ && sh ./hashScp.sh {$this->host} {$this->pwd} {$this->db} {$key} '{$hKeys}'";
        $num = system($cmd);
        Log::debug("[hash][{$key}]受影响条数: {$num}[success]");
        return true;
    }

    public function del($keys, $k)
    {
        $keys = implode(" ", $keys);
        $cmd = "cd " . __DIR__ . "/Script/ && sh ./strScp.sh {$this->host} {$this->pwd} {$this->db} '{$keys}'";
        $num = system($cmd);
        Log::debug("[string][{$k}*]受影响条数: {$num}[success]");
        return true;
    }

    public function close()
    {
        return $this->redis->close();
    }

}
