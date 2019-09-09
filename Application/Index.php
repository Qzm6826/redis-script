<?php

namespace App;

class Index extends Init {

    public function processHash($key)
    {
        if ($this->getKeyLength($key) == 0){
            return false;
        }
        $this->processHashData($key);
        return true;
    }

    public function processKey($key)
    {
        $this->processRedisData($key);
        return true;
    }

}
