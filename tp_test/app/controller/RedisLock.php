<?php


namespace app\controller;

use think\cache\driver\Redis;

class RedisLock
{
    private object $redis; // Redis 连接对象
    private string $key; // 锁的键名
    private int $expire; // 锁的过期时间

    // 构造函数，初始化 Redis 连接和锁的键名和过期时间
    public function __construct(string $host = 'redis', $password = '3qP9gPp7pr0jkXLo', int $port = 6379, int $expire = 100)
    {
        $this->redis = new Redis(['host'=>$host,'port'=>$port,'password'=>$password,'expire'=>$expire]);
        $this->key = 'mylock';
        $this->expire = $expire;
    }

    // 获取锁
    public function lock()
    {
        while (true) {
            $ret = $this->redis->set($this->key, date('Y-m-d H:i:s.u'), ['nx', 'ex' => $this->expire]);
            $date = date('H:i:s.u', time());
            if ($ret == 'OK') {
                echo "获取锁成功{$date}".PHP_EOL;
                break;
            }
            echo "获取锁失败，等待后重试...{$date}".PHP_EOL;
            usleep(500000); // 等待 0.5 秒后再次尝试获取锁
        }
    }

    // 释放锁， 被锁住的资源必须解锁才能进行访问（编辑），不然就会一直等待到该key过期才可以进行访问（编辑）
    public function unlock()
    {
        $this->redis->del($this->key);
        $date = date('H:i:s.u', time());
        echo "释放锁成功{$date}".PHP_EOL;
    }
}