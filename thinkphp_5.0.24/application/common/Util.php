<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/9
 * Time: 17:05
 */

namespace app\common;


class Util
{
    public static function show($code = 0, $errorMsg = 'ok', $data = [])
    {
        $respone = [
            'errorCode' => $code,
            'errorMsg' => $errorMsg,
            'data' => $data
        ];

        echo json_encode($respone, true);
    }
}