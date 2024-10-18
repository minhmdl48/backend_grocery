<?php

namespace App\Services;

use App\Repositories\Order\OrderRepository;
use App\Repositories\User\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $user_repo,
        protected OrderRepository $order_repo
    ) {}

    public function getUserLogin($id)
    {
        return $this->user_repo->getUserLogin($id);
    }

    public function find($id)
    {
        return $this->user_repo->find($id);
    }

    public function getUserAll($params)
    {
        return $this->user_repo->getAllUser($params);
    }

    public function createUser($params)
    {
        return $this->user_repo->create($params);
    }

    public function deleteUser($id)
    {
        return $this->user_repo->delete($id);
    }

    public function getHistoryByUser($user_id, $params)
    {
        return $this->order_repo->getHistoryByUser($user_id, $params);
    }

    public function getHistory($params)
    {
        return $this->order_repo->getHistory($params);
    }
}
