<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/10
 * Time: 10:07
 */

namespace app\common;


use app\index\server\smsService;

class Task
{
    public function sendSms($data)
    {
        if (!key_exists('phone', $data))
        {
            return false;
        }

        return smsService::sendSms($data['phone']);
    }
}