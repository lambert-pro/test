<?php
#Redis分布式锁测试2


require 'redis/RedisLock.php';
$lock = new RedisLock();
$lock->lock();
// do something，即需要被锁保护的代码
sleep(2);
$lock->unlock();
echo 2;