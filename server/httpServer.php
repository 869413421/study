<?php

$httpServer = new swoole_http_server('0.0.0.0', 8811);

//设置document_root并设置enable_static_handler为true后，底层收到Http请求会先判断document_root路径下是否存在此文件，如果存在会直接发送文件内容给客户端，不再触发onRequest回调
$httpServer->set([
    'document_root' => '/root/study/static/', // v4.4.0以下版本, 此处必须为绝对路径
    'enable_static_handler' => true,
]);

//接受请求的回调函数
$httpServer->on('request', function (swoole_http_request $request, swoole_http_response $response)
{
    $get = $request->get;
    $response->end('get数据：' . json_encode($get));
});


$httpServer->start();