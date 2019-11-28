<?php

use Swoole\Process;

//开启一个子进程，子进程中执行一个PHP脚本
$process = new Process(function (Process $pro) {
    $pro->exec('/root/work/soft/PHP7.3/bin/php', [
        __DIR__ . "/../server/wbServer.php"
    ]);
}, true);

$pid = $process->start();
echo '子进程ID：' . $pid;

//当主进程结束的时候会自动回收子进程
Process::wait();