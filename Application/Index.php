<?php

namespace App;

class Index extends Init {

    public function processHash($key)
    {
        if ($this->getKeyLength($key) == 0){
            return false;
        }
        $data = $this->getHashData($key);
        if (empty($data)){
            return false;
        }
        $hKeys = array_keys($data);
        $this->hDel($key, $hKeys);
        return true;
    }

    public function processKey($key)
    {
        $data = $this->getRedisData($key);
        if (empty($data)){
            return false;
        }
        $strKeys = array();
        foreach ($data as $val){
            $type = $this->getType($val);     //int 1 string or int 5 hash
            if ($type == 1) {
                $strKeys []= $val;
            }
            if ($type == 5) {
                $this->iterator = null;
                $this->processHash($val);
                Log::warn("[hash]--keys:{$val}");
            }
            continue;
        }
        if (empty($strKeys)) {
            return false;
        }
        $this->del($strKeys, $key);
        return true;
    }

}
