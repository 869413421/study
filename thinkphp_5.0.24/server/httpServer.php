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

        $this->instance->on('workerstart', [$this, 'onWorkerStart']);
        $this->instance->on('request', [$this, 'onRequest']);
        $this->instance->on('task', [$this, 'onTask']);
        $this->instance->start();
    }

    public function onWorkerStart(Server $server, $worker_id)
    {
        define('APP_PATH', __DIR__ . '/../application/');

        require __DIR__ . '/../thinkphp/start.php';
    }

    public function onRequest(Request $request, Response $response)
    {
        $this->initGlobals($request, $response);

        ob_start();
        try
        {
            \think\App::run()->send();
        }
        catch (Exception $exception)
        {
            var_dump($exception->getMessage());
            $response->end($exception->getMessage());
            return;
        }
        $result = ob_get_contents();
        ob_end_clean();
        $response->end($result);
    }

    public function onTask(Server $server, $taskId, $workerId, $data)
    {
        $obj = new \app\common\Task();
        $method = $data['method'];
        return $obj->$method($data['data']);
    }

    /***
     * 因为swoole不会释放掉PHP中的常驻内存，所以要初始化掉需要用到的全局变量
     * @param Request $request
     */
    private function initGlobals(Request $request, Response $response)
    {
        if ($request->server['request_uri'] == '/favicon.ico')
        {
            $response->status(404);
            $response->end();
            return;
        }

        $_SERVER = [];
        if (isset($request->server))
        {
            foreach ($request->server as $k => $v)
            {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if (isset($request->header))
        {
            foreach ($request->header as $k => $v)
            {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if (isset($request->get))
        {
            foreach ($request->get as $k => $v)
            {
                $_GET[$k] = $v;
            }
        }

        $_FILES = [];
        if (isset($request->files))
        {
            foreach ($request->files as $k => $v)
            {
                $_FILES[$k] = $v;
            }
        }

        $_POST = [];
        if (isset($request->post))
        {
            foreach ($request->post as $k => $v)
            {
                $_POST[$k] = $v;
            }
        }

        $_POST['httpServer'] = $this->instance;//把对像传过去
    }
}

$server = new httpServer();