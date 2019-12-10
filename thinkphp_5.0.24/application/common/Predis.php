<?php
/**
 * Created by PhpStorm.
 * User: 、、、、、、、、、、、
 * Date: 2019/12/9
 * Time: 22:42
 */

namespace app\common;


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
        $connect = $this->redis->connect(config('redis.host'), config('redis.port'));
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
}