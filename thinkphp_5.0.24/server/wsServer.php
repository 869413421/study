<?php
/**
 * Created by PhpStorm.
 * User: 简美
 * Date: 2019/12/3
 * Time: 17:59
 */

use app\common\Predis;
use Swoole\Coroutine;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;
use think\Config;

class wsServer
{
    protected $host = "0.0.0.0";
    protected $port = 8811;
    protected $setting = [
        'document_root' => '/root/study/static', // v4.4.0以下版本, 此处必须为绝对路径
        'enable_static_handler' => true,
        'worker_num' => 5,
        'task_worker_num' => 4,
    ];
    protected $instance = null;

    public function __construct()
    {
        $this->initServer();
    }

    private function initServer()
    {
        $this->instance = new Swoole\WebSocket\Server($this->host, $this->port);
        $this->instance->set($this->setting);
        $this->instance->listen($this->host, 8812, SWOOLE_SOCK_TCP);

        $this->instance->on('start', [$this, 'onStart']);
        $this->instance->on('workerstart', [$this, 'onWorkerStart']);
        $this->instance->on('open', [$this, 'onOpen']);
        $this->instance->on('message', [$this, 'onMessage']);
        $this->instance->on('close', [$this, 'onClose']);
        $this->instance->on('request', [$this, 'onRequest']);
        $this->instance->on('task', [$this, 'onTask']);
        $this->instance->on('finish', [$this, 'onFinish']);
        $this->instance->start();
    }

    public function onStart()
    {
        swoole_set_process_name('live_master');
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
        try {
            \think\App::run()->send();
        } catch (Exception $exception) {
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

    public function onFinish($server, $task_id, $data)
    {
        echo "$data\n";
    }

    public function onOpen($server, $request)
    {
        print_r("客户端: {$request->fd} 连接到服务器\n");

//        $num = 1;
//        //毫秒级定时器的操作是异步非阻塞的
//        swoole_timer_tick(2000, function ($timer_id) use ($server, $request, $num) {
//            if ($num == 20) {
//                swoole_timer_clear($timer_id);
//            }
//            $server->push($request->fd, '定时器tick发送的数据' . $num);
//        });
        Predis::getInstance()->sAdd(Config::get('live.connect_key'), $request->fd);
    }

    public function onMessage($server, $frame)
    {
        print_r("推送任务，开始\n");

        $this->instance->task([
            'id' => 1,
            'user_name' => 'xiaohu'
        ]);

        swoole_timer_after(5000, function () use ($server, $frame) {
            $server->push($frame->fd, '定时器after发送的数据');
        });

        print_r("客户端： {$frame->fd} 向服务器发送了信息\n$frame->data" . "\n");
        $server->push($frame->fd, "服务端回复了信息:$frame->data" . date('Y-m-d H:i:s') . "\n");
    }

    public function onClose($server, $fd)
    {
        Predis::getInstance()->sRem(Config::get('live.connect_key'), $fd);
        print_r("客户端 $fd 断开了连接\n");
    }

    /***
     * 因为swoole不会释放掉PHP中的常驻内存，所以要初始化掉需要用到的全局变量
     * @param Request $request
     */
    private function initGlobals(Request $request, Response $response)
    {
        //过滤无用请求
        if ($request->server['request_uri'] == '/favicon.ico') {
            $response->status(404);
            $response->end();
            return;
        }

        $_SERVER = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        if (isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }

        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }
        $this->writeLog();
        $_POST['httpServer'] = $this->instance;//把对像传过去
    }

    /***
     * 记录请求日志
     */
    private function writeLog()
    {
        $data = array_merge(['data' => date("Ymd H:i:s"), $_GET, $_POST, $_SERVER]);

        $logs = '';
        foreach ($data as $key => $value) {
            $logs .= $key . " " . $value . " ";
        }
        $logs .= PHP_EOL;

        $fileName = APP_PATH . '../runtime/' . date('Ym') . '/' . date('d') . '_log.txt';
        Coroutine::writeFile($fileName, $logs, FILE_APPEND);
    }
}

new wsServer();