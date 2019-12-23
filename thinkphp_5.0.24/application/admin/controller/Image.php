<?php

namespace app\admin\controller;

use app\common\Util;
use think\Config;

class Image
{
    public function index()
    {
        $file = request()->file('file');
        $info = $file->move('../../static/upload');

        if (!$info) {
            return Util::show(-100, '上传失败');
        }

        $data = [
            'image' => Config::get('app.host') . 'upload/' . $info->getSaveName()
        ];

        return Util::show(0, 'ok', $data);
    }
}
