<?php

namespace App\Repositories\Message;

use App\Models\Message;
use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{
    public function __construct(Message $model)
    {
        $this->model = $model;
    }

    public function getChatGroup($user, $userChat)
    {
        $group = $this->model
            ->where(function ($query) use ($user, $userChat) {
                $query->where('user_id', $user)
                    ->where('chat_with', $userChat);
            })
            ->orWhere(function ($query) use ($user, $userChat) {
                $query->where('user_id', $userChat)
                    ->where('chat_with', $user);
            })
            ->with(['user', 'chatWithUser'])
            ->get();

        return $group;
    }

    public function createMessage($user_id, $chat_with, $message)
    {
        return $this->model->create([
            'user_id' => $user_id,
            'chat_with' => $chat_with,
            'type' => "default",
            'text' => $message,
        ]);
    }
}
