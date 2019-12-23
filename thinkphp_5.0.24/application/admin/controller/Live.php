<?php
/**
 * Created by PhpStorm.
 * User: 、、、、、、、、、、、
 * Date: 2019/12/23
 * Time: 22:19
 */

namespace app\admin\controller;


class Live
{
    public function index()
    {
        $data = json_encode($_GET);
        return $data;
    }
}