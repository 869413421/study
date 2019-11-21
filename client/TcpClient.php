<?php

//创建一个客户端并且指定协议
$client = new swoole_client(SWOOLE_SOCK_TCP);

if(!$client->connect('127.0.0.1', 9501)){
    echo '连接失败';
    exit();
}

//接收CLI模式下用户输入内容
fwrite(STDOUT, '请输入：');
$msg=trim(fgets(STDIN));

//发送内容到客户端
$client->send($msg);

//接收客户端返回的内容并且输出
$result = $client->recv();
echo $result;