<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/10
 * Time: 11:32
 */

namespace app\index\server;


use app\common\Predis;

class loginService
{
    CONST userKey = 'user_';

    public static function login($phone, $code)
    {
        $redis = Predis::getInstance();
        $value = $redis->get(smsService::smsKey . $phone);

        if (empty($value))
        {
            return false;
        }

        if ($value != $code)
        {
            return false;
        }

        $data = [
            'phone' => $phone,
            'login_status' => true,
            'login_ip' => $_SERVER['REMOTE_ADDR']
        ];
        $redis->set(self::userKey . $phone, $data);
        return true;
    }
}