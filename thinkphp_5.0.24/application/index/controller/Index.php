<?php

namespace app\index\controller;

use app\common\Sms;

class Index
{
    public function index()
    {
        return Sms::SendSms('13528685024');
    }
}
