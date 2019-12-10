<?php

namespace app\index\controller;

use app\common\Predis;
use app\common\Util;
use app\index\server\loginService;
use app\index\server\smsService;
use think\Log;

class Login
{
    public function index()
    {
        $phone = $_GET['phone_num'];
        $code = $_GET['code'];

        if (!$phone || !$code)
        {
            return Util::show(-100, 'phonenum or code null');
        }

        if (!loginService::login($phone, $code))
        {
            return Util::show(-200, 'code error');
        }

        return Util::show();
    }
}
