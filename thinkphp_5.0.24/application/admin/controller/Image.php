<?php

namespace app\index\controller;

class Image
{
    public function index()
    {
        $file = request()->file('file');
        $info = $file->move('../../static/upload');
        var_dump($info);
    }
}
