<?php
/**
 * Created by PhpStorm.
 * User: 、、、、、、、、、、、
 * Date: 2019/12/9
 * Time: 22:42
 */

namespace app\common;


use think\Config;

class Predis
{
    public $redis = null;

    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->redis = new \Redis();
        $connect = $this->redis->connect(Config::get('redis.host'), Config::get('redis.port'));
        if (!$connect) {
            throw new \Exception('connect error');
        }
    }

    private function __clone()
    {

    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function set($key, $value, $time = 0)
    {
        if (!$key || !$value) {
            throw new \Exception('key or value null');
        }

        if (is_array($value)) {
            $value = json_encode($value, true);
        }

        if (!$time) {
            return $this->redis->set($key, $value);
        }

        return $this->redis->setex($key, $time, $value);
    }

    public function sAdd($key, $value)
    {
        $this->redis->sAdd($key, $value);
    }

    public function sRem($key, $value)
    {
        $this->redis->sRem($key, $value);
    }

    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }
}