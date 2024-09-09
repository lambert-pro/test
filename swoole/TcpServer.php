<?php

$tcp = new Swoole\Server('0.0.0.0', 9501);
$tcp->on('Connect', function ($server, $fd){
	echo "Client connect. \n";
});

$tcp->on('Receive', function ($server, $fd, $reactor_id, $data){
	$server->send($fd, "Server: {$data}");
});

$tcp->on('Close',  function ($server, $fd){
	echo "Client close. \n";
});

$tcp->start();