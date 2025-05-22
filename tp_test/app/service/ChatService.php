<?php

namespace app\service;

use think\facade\Cache;

class ChatService
{

    protected string $prefix = 'chat_room';
    protected int $historyLimit = 30;

    public function setUser(int $fd, string $nickname)
    {
        Cache::store('redis')->set($this->prefix.'user:'.$fd, $nickname);
    }

    public function getUser(int $fd):? string
    {
        return Cache::store('redis')->get($this->prefix.'user:'.$fd);
    }

    public function deleteUser(int $fd)
    {
        Cache::store('redis')->delete($this->prefix.'user:'.$fd);
    }

    public function getOnlineUser(): array
    {
        $keys = Cache::store('redis')->keys($this->prefix.'user:*');
        $users = [];
        foreach ($keys as $key) {
            $fd = str_replace($this->prefix.'user:', '', $key);
            $users[$fd] = Cache::store('redis')->get($key);
        }
        return $users;
    }

    public function addMessage(string $message)
    {
        Cache::store('redis')->rPush($this->prefix.'message', $message);
        Cache::store('redis')->lTrim($this->prefix.'message', -$this->historyLimit, -1);
    }

    public function getMessage(): array
    {
        return Cache::store('redis')->lRange($this->prefix.'message', 0, -1);
    }

}