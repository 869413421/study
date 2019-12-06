<?php

namespace app\sms\controller;

use app\sms\server\smsService;

class Index
{
    public function index()
    {
        $phone = $_GET['phone_num'];

        if (!smsService::sendSms($phone))
        {

        }
    }
}
