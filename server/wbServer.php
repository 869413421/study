<?php

class wbServer
{
    private $HOST = '0.0.0.0';
    private $PORT = 8811;
    private $instance = null;

    public function __construct()
    {
        $this->instance = new swoole_websocket_server($this->HOST, $this->PORT);

        $this->instance->set([
            'document_root' => '/root/study/static/', // v4.4.0以下版本, 此处必须为绝对路径
            'enable_static_handler' => true,
        ]);

        $this->instance->on('open', [$this, 'onOpen']);
        $this->instance->on('message', [$this, 'onMessage']);
        $this->instance->on('close', [$this, 'onClose']);

        $this->instance->start();
    }

    public function onOpen($server, $request)
    {
        print_r("客户端: {$request->fd} 连接到服务器");
    }

    public function onMessage($server, $frame)
    {
        print_r("客户端： {$frame->fd} 向服务器发送了信息\n$frame->data");
        $server->push($frame->fd, "服务端回复了信息:$frame->data" . date('Y-m-d H:i:s'));
    }

    public function onClose($server, $fd)
    {
        print_r("客户端 $fd 断开了连接");
    }
}

$server = new wbServer();