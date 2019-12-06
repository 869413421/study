<?php

namespace app\index\controller;

use app\common\Sms;

class Index
{
    public function index()
    {
        $code = rand(1000, 9999);
        $data = Sms::SendSms('13528685024', 'code:' . $code);
        var_dump($data);
        echo json_encode($data);
    }
}
