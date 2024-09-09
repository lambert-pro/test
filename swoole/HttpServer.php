<?php
date_default_timezone_set('Asia/Shanghai');
$http = new \Swoole\Http\Server('0.0.0.0',9501);

$http->on('request', function ($request, $response){
	echo '接受到请求'.PHP_EOL;
	print_r($_SERVER);
	$response->header('Content-Type','text/html; charset=utf-8');
	$response->end("<h1>hello swoole! #".date('Y-m-t H:i:s')."</h1>");
});

echo '服务启动';
$http->start();