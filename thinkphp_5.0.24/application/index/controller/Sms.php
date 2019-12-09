<?php

namespace app\index\controller;

use app\common\Predis;
use app\common\Util;
use app\index\server\smsService;

class Sms
{
    public function index()
    {
        $phone = $_GET['phone_num'];

        if (!$code = smsService::sendSms($phone)) {
            return Util::show(200, 'sendsms error');
        }

        $key = 'phone_' . $phone;
        Predis::getInstance()->set($key, $code, 300);
        return Util::show();
    }
}
