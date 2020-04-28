<?php
namespace App\Http\Services;
use Redis;

class RedisCache{
    public $redis;
    
    public function __construct(){
        $redis_credentials = config('services.redis');

        $this->redis = new Redis();
        $this->redis->connect($redis_credentials['host'], $redis_credentials['port'], 1, NULL, 100);  // 1 sec timeout, 100ms delay between reconnection attempts.
        $this->redis->auth($redis_credentials['password']);
    }

    public function getKey($key){
        return unserialize($this->redis->get($key));
    }

    //$expires_at = 79200 //expires at 22 hours
    public function setKey($key, $value, $expires_at = 79200){
        return $this->redis->setEx($key, $expires_at, serialize($value) );
    }

    public function exists($key){
        return $this->redis->exists($key);
    }

    public function deleteKey($key){
        return $this->redis->unlink($key);
    }
}