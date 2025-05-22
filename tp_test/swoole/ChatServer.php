<?php
namespace swoole;

use app\service\ChatService;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use think\App;
use think\facade\Cache;

class ChatServer
{
    protected Server $server;
    protected App $app;

    public function __construct() {
        $this->app = new App();
        $this->app->initialize();

        $this->server = new Server('0.0.0.0', 9501);
        $this->registerEvents();
    }

    private function registerEvents()
    {
        $this->server->on('start', function (){
            echo "Swoole Server started at ws://0.0.0.0:9501\n";
        });

        $this->server->on('open', function (Server $server, Request $request){
            echo "Connection open: {$request->fd}\n";

            $messages = (new ChatService())->getMessage();
            foreach ($messages as $msg) {
                $server->push($request->fd, "历史记录：".$msg);
            }
        });

        $this->server->on('message', function (Server $server, Frame $frame){
            $msg = $frame->data;
            $response = $this->app->make(\app\controller\Chat::class)->onMessage($frame->fd, $msg);
            $server->push($frame->fd, $response);
        });

        $this->server->on('close', function (Server $server, int $fd){
            echo "Connection close: {$fd}\n";
            (new ChatService())->deleteUser($fd);
        });
    }

    public function start()
    {
        $this->server->start();
    }

}