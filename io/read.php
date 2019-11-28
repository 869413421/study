<?php
/**
 * Created by PhpStorm.
 * User: 、、、、、、、、、、、
 * Date: 2019/11/28
 * Time: 22:00
 */

use Swoole\Async;

//readfile会将所有内容读取到内存中，最多可以读取4M,所以不可以用来读取大文件.
Async::readfile(__DIR__ . "/testRead.txt", function ($fileName, $fileContent) {
    echo '文件名：' . $fileName . PHP_EOL;
    echo '文件内容：' . $fileContent . PHP_EOL;
});
