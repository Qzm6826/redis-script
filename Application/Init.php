<?php

namespace App;

class Init{

    private $redis = null;
    private $iterator = null;
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

    public function getHashData($hKey)
    {
        if ($this->redis->hLen($hKey) == 0){
            return false;
        }
        $this->iterator = null;
        return $this->redis->hScan($hKey, $this->iterator, "*", 6500);
    }

    public function getRedisData($key)
    {
        $this->iterator = null;
        return $this->redis->scan($this->iterator, $key . "*", 6500);
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
