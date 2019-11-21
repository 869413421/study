<?php

$httpServer = new swoole_http_server('0.0.0.0', 8811);

//接受请求的回调函数
$httpServer->on('request', function (swoole_http_request $request, swoole_http_response $response) {
    $get = $request->get;
    $response->end('get数据：' . json_encode($get));
});

$httpServer->start();