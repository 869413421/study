<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/3
 * Time: 17:59
 */

use Swoole\Http\Server;

class httpServer
{
    protected $host = "0.0.0.0";
    protected $port = 8811;
    protected $instance = null;

    public function __construct()
    {

    }

    private function initServer()
    {
        $this->instance = new Server($this->host, $this->port);

        $this->instance->on('request', [$this, 'onRequest']);
    }

    private function onRequest()
    {

    }
}