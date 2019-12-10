<?php

namespace app\index\controller;

class Index
{
    public function index()
    {
        echo json_encode($_GET);
    }
}
