<?php

namespace app\index\controller;

use app\common\Sms;

class Index
{
    public function index()
    {
        $code = rand(1000, 9999);
        return Sms::SendSms('13528685024', 'code:' . $code);
    }
}
