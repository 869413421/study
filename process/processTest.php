<?php
/**
 * Created by PhpStorm.
 * User: 、、、、、、、、、、、
 * Date: 2019/12/2
 * Time: 21:34
 */

use Swoole\Process;

$urls = [
    'https://wiki.swoole.com/wiki/page/p-process.html',
    'https://www.baidu.com'
];

$worker_arr = [];
foreach ($urls as $url) {
    $process = new Process(function (Process $process) use ($url) {
        $content = getUrlContent($url);
        $process->write($content);
    });

    $pid = $process->start();
    $worker_arr[$pid] = $process;
}

foreach ($worker_arr as $pid => $process) {
    $content = $process->read();
    putContentToFile($content);
}

function getUrlContent($url)
{
    return file_get_contents($url);
}

function putContentToFile($content)
{
    $path = __DIR__ . '../static/html.txt';
    file_put_contents($path, $content, FILE_APPEND);
}