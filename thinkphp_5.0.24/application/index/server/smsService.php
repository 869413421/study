<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/6
 * Time: 18:01
 */

namespace app\index\server;


use app\common\Predis;
use app\common\Sms;

class smsService
{
    CONST smsKey = 'phone_';

    /***
     * 发送短信业务
     * @param $phone
     * @return bool
     * @throws \Exception
     */
    public static function sendSms($phone)
    {
        //生成验证码
        $code = rand(1000, 9999);
        //调用第三方短信发送接口
        $result = Sms::SendSms($phone, 'code:' . $code);

        if ($result['return_code'] !== '00000')
        {
            file_put_contents(APP_PATH . 'log/' . date('Y-m-d') . '/' . 'log.txt', $result['return_code'] . PHP_EOL, FILE_APPEND);
            return false;
        }
        //发送成功将数据插入redis
        $key = self::smsKey . $phone;
        Predis::getInstance()->set($key, $code, 300);
        return true;
    }
}