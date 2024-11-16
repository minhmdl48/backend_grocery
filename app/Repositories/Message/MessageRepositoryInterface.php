<?php

namespace App\Repositories\Message;

interface MessageRepositoryInterface
{
    public function getChatGroup($user, $userChat);
    public function createMessage($user_id, $chat_with, $message);
}
