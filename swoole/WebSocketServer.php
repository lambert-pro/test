<?php
require './SetTimeZone.php';

$ws = new Swoole\WebSocket\Server('0.0.0.0', 9501);

$ws->on('Open', function ($ws, $request){
	$fd = $request->fd;
	echo "Client-{$fd} is online\n";
	// 使用子协程来处理持续推送消息，避免阻塞事件循环
	Swoole\Timer::tick(10000, function() use ($ws, $fd) {
		// 在推送消息之前检查fd是否存在
		if ($ws->exist($fd)) {
			$time = date('Y-m-d H:i:s');
			$ws->push($fd, "Fd : {$fd}");
			$ws->push($fd, "Hello, welcome! #{$time}");
		} else {
			// 如果fd不存在，停止推送
			Swoole\Timer::clearAll();
		}
	});
});

$ws->on('Message', function ($ws, $frame){
	echo "Message: {$frame->data}\n";
	$ws->push($frame->fd, "Sever got your msg: {$frame->data}");
});

$ws->on('Close', function ($ws, $fd){
	echo "Client-{$fd} is close\n";
});

$ws->start();