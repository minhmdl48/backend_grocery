<?php

namespace App\Services;

use App\Repositories\Order\OrderRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Message\MessageRepository;
use Illuminate\Support\Facades\Auth;

class MessageService
{
    public function __construct(
        protected MessageRepository $messageRepository,
    )
    {
    }

    public function getChatGroup($id)
    {
        $user_id = Auth::id();
        return $this->messageRepository->getChatGroup($id, $user_id);
    }

    public function createMessage($chat_with, $message)
    {
        $user_id = Auth::id();
        return $this->messageRepository->createMessage($user_id, $chat_with, $message);
    }

}
