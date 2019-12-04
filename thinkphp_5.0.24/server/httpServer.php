<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/3
 * Time: 17:59
 */

use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

class httpServer
{
    protected $host = "0.0.0.0";
    protected $port = 8811;
    protected $setting = [
        'document_root' => '/root/study/static/live', // v4.4.0以下版本, 此处必须为绝对路径
        'enable_static_handler' => true,
        'worker_num' => 5
    ];
    protected $instance = null;

    public function __construct()
    {
        $this->initServer();
    }

    private function initServer()
    {
        $this->instance = new Server($this->host, $this->port);
        $this->instance->set($this->setting);

        $this->instance->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->instance->on('request', [$this, 'onRequest']);
        $this->instance->start();
    }

    private function onWorkerStart(Server $server, $worker_id)
    {
        define('APP_PATH', __DIR__ . '/../application/');

        require __DIR__ . '../thinkphp/base.php';
    }

    private function onRequest(Request $request, Response $response)
    {
        $this->initGlobals($request);

        ob_start();
        try
        {
            \think\App::run()->send();
        }
        catch (Exception $exception)
        {

        }
        $result = ob_get_contents();
        ob_end_clean();

        $response->end($result);
    }

    /***
     * 因为swoole不会释放掉PHP中的常驻内存，所以要初始化掉需要用到的全局变量
     * @param Request $request
     */
    private function initGlobals(Request $request)
    {
        $_SERVER = [];
        foreach ($request->server as $key => $value)
        {
            $_SERVER[strtoupper($key)] = $value;
        }

        $_GET = [];
        foreach ($request->get as $key => $value)
        {
            $_GET[$key] = $value;
        }

        $_POST = [];
        foreach ($request->post as $key => $value)
        {
            $_POST[$key] = $value;
        }
    }
}

$server = new httpServer();