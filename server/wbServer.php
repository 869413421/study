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
            'worker_num' => 2,
            'task_worker_num' => 2
        ]);

        $this->instance->on('open', [$this, 'onOpen']);
        $this->instance->on('message', [$this, 'onMessage']);
        $this->instance->on('close', [$this, 'onClose']);

        //要使用task必须先设置worker_num和task_worker_num
        //任务会推送到task_work中阻塞处理，完成后才会接受下一个任务。
        $this->instance->on('task', [$this, 'onTask']);
        $this->instance->on('finish', [$this, 'onFinish']);

        $this->instance->start();
    }

    public function onOpen($server, $request)
    {
        print_r("客户端: {$request->fd} 连接到服务器\n");

        $num = 1;
        //毫秒级定时器的操作是异步非阻塞的
        swoole_timer_tick(2000, function ($timer_id) use ($server, $request, $num) {
            if ($num == 20) {
                $server->push($request->fd, '定时器tick发送的最后一条数据' . $num);
                swoole_timer_del($timer_id);
            }
            $server->push($request->fd, '定时器tick发送的数据' . $num);
        });
    }

    public function onMessage($server, $frame)
    {
        print_r("推送任务，开始\n");

        $this->instance->task([
            'id' => 1,
            'user_name' => 'xiaohu'
        ]);

        swoole_timer_after(5000, function () use ($server, $frame) {
            $server->push($frame->fd, '定时器after发送的数据');
        });

        print_r("客户端： {$frame->fd} 向服务器发送了信息\n$frame->data" . "\n");
        $server->push($frame->fd, "服务端回复了信息:$frame->data" . date('Y-m-d H:i:s') . "\n");
    }

    public function onClose($server, $fd)
    {
        print_r("客户端 $fd 断开了连接\n");
    }

    public function onTask($server, $task_id, $work_id, $data)
    {
        echo "接收到任务\n";
        for ($i = 0; $i < 10; $i++) {
            sleep(1);
            echo $i . "\n";
        }
        echo "数据是：\n";
        var_dump($data);

        return '我是来自task_worker的数据';
    }

    public function onFinish($server, $task_id, $data)
    {
        echo "$data\n";
    }
}

$server = new wbServer();