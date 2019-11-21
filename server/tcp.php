<?php

//开启服务。
$server = new swoole_server('127.0.0.1', 9501);

//设置swoole运行时的参数
$server->set([
    //启动work的进程数量，一般设置为CPU的1到4倍
    'worker_num' => 2,
    //max_request => 2000，此参数表示worker进程在处理完n次请求后结束运行。manager会重新创建一个worker进程。此选项用来防止worker进程内存溢出。
    'max_request' => 100
]);

/**
 * 客户端连接到服务器的回调参数
 * $fd 客户端连接的唯一标识
 * $reactor_id reactor线程的id
 */
$server->on('connect', function ($server, $fd, $reactor_id) {
    echo "用户唯一标识：{$fd},当前reactor线程id:{$reactor_id}";
});

/**
 * 监听客户端的回调函数
 */
$server->on('receive', function ($server, $fd, $reactor_id, $data) {
    $server->send($fd, "用户唯一标识：{$fd},当前reactor线程id:{$reactor_id},客户端发送的数据为：{$data}");
});

$server->on('close', function ($server, $fd) {
    echo "用户唯一标识：{$fd},断开连接";
});

$server->start();