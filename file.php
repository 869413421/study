<?php

fwrite(STDOUT, '请备份后再进行操作，请输入目录路径:' . PHP_EOL);
$path = str_replace('\\', '/', trim(fgets(STDIN)));

if (!file_exists($path))
{
    echo '请输入正确目录';
    exit();
}

createCocosDir($path);
moveJson($path);
moveImg($path);
echo '修改成功';


function moveJson($path)
{
    $json_files = [];
    $json_path = $path . '/' . 'res' . '/import';
    getFile($json_path, $json_files);

    foreach ($json_files as $file_json)
    {
        $name_arr = explode('/', $file_json);
        $name = $name_arr[count($name_arr) - 1];
        copy($file_json, $path . '/res/' . $name);
    }
    deleteFile($json_path);
}

function moveImg($path)
{
    $files = [];
    $file_path = $path . '/' . 'res' . '/raw-assets';
    getFile($file_path, $files);

    foreach ($files as $file)
    {
        $name_arr = explode('/', $file);
        $name = $name_arr[count($name_arr) - 1];
        copy($file, $path . '/SYS_cocos/' . $name);
    }
    deleteFile($file_path);
}

function getFile($dir, &$files)
{
    //检测是否存在文件
    if (is_dir($dir))
    {
        //打开目录
        if ($handle = opendir($dir))
        {
            //返回当前文件的条目
            while (($file = readdir($handle)) !== false)
            {
                //去除特殊目录
                if ($file != "." && $file != "..")
                {
                    //判断子目录是否还存在子目录
                    if (is_dir($dir . "/" . $file))
                    {
                        //递归调用本函数，再次获取目录
                        getFile($dir . "/" . $file, $files);
                    }
                    else
                    {
                        //获取目录数组
                        $files[] = $dir . "/" . $file;
                    }
                }
            }
            //关闭文件夹
            closedir($handle);
        }
    }
}

function deleteFile($path)
{

    if (is_dir($path))
    {
        //扫描一个目录内的所有目录和文件并返回数组
        $dirs = scandir($path);

        foreach ($dirs as $dir)
        {
            //排除目录中的当前目录(.)和上一级目录(..)
            if ($dir != '.' && $dir != '..')
            {
                //如果是目录则递归子目录，继续操作
                $sonDir = $path . '/' . $dir;
                if (is_dir($sonDir))
                {
                    //递归删除
                    deleteFile($sonDir);
                    //目录内的子目录和文件删除后删除空目录
                    @rmdir($sonDir);
                }
                else
                {
                    //如果是文件直接删除
                    @unlink($sonDir);
                }
            }
        }
        @rmdir($path);
    }
}

function createCocosDir($path)
{
    $dir_path = $path . '/SYS_cocos';
    if (!file_exists($dir_path))
    {
        mkdir($dir_path);
    }
}