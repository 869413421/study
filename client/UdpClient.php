<?php

$client = new swoole_client(SWOOLE_SOCK_UDP);

if (!$client->connect('127.0.0.1', 9502)) {
    echo '连接失败';
    exit();
}

fwrite(STDOUT, '请输入:');
$msg = trim(fgets(STDIN));
$client->send($msg);

$result = $client->recv();
echo $result;