<?php
/**
 * Created by PhpStorm.
 * User: 、、、、、、、、、、、
 * Date: 2019/12/2
 * Time: 22:41
 */

use Swoole\Coroutine\MySQL;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$httpServer = new Server('0.0.0.0', 8811);

$httpServer->on('request', function (Request $request, Response $response) {
    //协程MYSQL必须要在request等回调函数中执行
    $mySql = new MySQL([
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'ym',
        'password' => 'Ym135168.',
        'database' => 'ymbbs',
    ]);

    $result = $mySql->query('SELECT * FROM users');
    $response->end(json_encode($result));
});

$httpServer->start();