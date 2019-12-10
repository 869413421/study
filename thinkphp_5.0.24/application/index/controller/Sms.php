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

        if (!$phone)
        {
            return Util::show(-100, 'phonenum null');
        }

        $data = [
            'method' => 'sendSms',
            'data' => [
                'phone' => $phone
            ]];

        $_POST['httpServer']->task($data);

        return Util::show();
    }
}
