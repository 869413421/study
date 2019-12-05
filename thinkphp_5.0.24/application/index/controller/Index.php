<?php

namespace app\index\controller;

class Index
{
    public function index()
    {
        return json_encode($_GET);
    }
}
