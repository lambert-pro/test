<?php
#Redis分布式锁测试1


require 'redis/RedisLock.php';
$lock = new RedisLock();
$lock->lock();
// do something，即需要被锁保护的代码
// 被锁住的资源，其他节点无法访问和修改
sleep(5);

$lock->unlock();