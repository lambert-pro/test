<?php

require './SetTimeZone.php';

$http = new Swoole\Http\Server('0.0.0.0', 9501);

/**
 * 如果要使用 Task ，需要先设置 task_worker_num ，它代表的是开启的 Task 进程数量。
 */
$http->set([ "task_worker_num" => 4]);

$http->on('Request', function ($request, $response) use ($http){
	echo PHP_EOL."接收到请求".PHP_EOL;
	$http->task('send email.');
	$http->task('send notice.');
	$http->task('send webhook.');
	$response->end('<h1>Hello Swoole. #' . rand(1000, 9999) . '</h1>');
});

// 处理异步任务，此回调函数在task进程中进行
$http->on('Task', function ($serv, $task_id, $reactor_id, $data){
	$sleepTime = rand(1,10);
	echo "新的异步任务[{$task_id} - {$data}], 睡眠时间为 {$sleepTime}s \n";
	//sleep() 中会阻塞当前 Task 进程
	sleep($sleepTime);
	$serv->finish("[{$task_id} - {$data}]完成");
});

// 处理异步任务的结果，此回调函数在worker进程中执行
$http->on('Finish', function ($serv, $task_id, $data){
	echo "异步任务[{$task_id}] ：{$data}\n";
});

echo "\n异步任务启动".PHP_EOL;
$http->start();