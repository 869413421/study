<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/24
 * Time: 10:11
 */

class ServerCheck
{
    public $port = 8811;

    public function checkPort()
    {
        $shell = "netstat -anp 2>/dev/null |grep {$this->port} |grep LISTEN |wc -l";

        $result = shell_exec($shell);

        if ($result < 1)
        {
            echo 'ERROR:端口' . $this->port . '已关闭' . PHP_EOL;
        }
        else
        {
            echo 'SUCCESS' . PHP_EOL;
        }
    }
}

$check = new ServerCheck();
swoole_timer_tick(2000, function () use ($check)
{
    $check->checkPort();
});