<?php

namespace app\index\controller;

use app\index\server\smsService;

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
