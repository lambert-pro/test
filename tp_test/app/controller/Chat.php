<?php

namespace app\controller;


use app\service\ChatService;

class Chat
{
    protected ChatService $chat;

    public function __construct() {
        $this->chat = new ChatService();
    }

    public function onMessage(int $fd, string $message): string
    {
        if (str_starts_with($message, '/nick ')){
            $nickname =  trim(substr($message, 6));
            $this->chat->setUser($fd, $nickname);
            return "设置昵称为：{$nickname}";
        }
        $user = $this->chat->getUser($fd) ?: "匿名{$fd}";
        $fullMessage = "{$user}: {$message}";
        $this->chat->addMessage($fullMessage);
        return $fullMessage;
    }

}