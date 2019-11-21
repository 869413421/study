<?php

$server = new swoole_server('127.0.0.1', 9502, SWOOLE_PROCESS, SWOOLE_UDP);

/***
 * 因为UPD没有连接的概念所以只需要实现监听接收报的回调函数
 */
$server->on('Packet', function ($server, $data, $clientInfo) {
    $server->sendto($clientInfo['address'], $clientInfo['port'], '回复：' . $data);
    var_dump($clientInfo);
});

$server->start();