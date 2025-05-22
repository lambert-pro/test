<?php

require __DIR__.'/vendor/autoload.php';

use swoole\ChatServer;

$server = new ChatServer();
$server->start();