<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/6
 * Time: 18:01
 */

namespace app\index\server;


use app\common\Sms;
use app\common\Util;

class smsService
{
    public static function sendSms($phone)
    {
        $code = rand(1000, 9999);
        $result = Sms::SendSms($phone, 'code:' . $code);

        if ($result['return_code'] !== '00000')
        {
            return Util::show(-100, 'sendsms fail');
        }
    }
}