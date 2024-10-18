<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\Order\OrderRepository;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(
        protected OrderRepository $order_repo
    ) {}

    public function createOrder($params)
    {
        $user_id = Auth::id();
        $transaction_id = rand(100000, 999999);
        $param_orders = [
            'user_id' => $user_id,
            'total_amount' => $params['total_amount'],
            'transaction_id' => $transaction_id,
            'phone' => $params['phone'],
            'address' => $params['address']
        ];

        return $this->order_repo->create($param_orders);
    }

    public function updateOrderByTransaction($transaction_id)
    {
        return $this->order_repo->updateOrderByTransaction($transaction_id);
    }

    public function updateOrderStatus($status, $id)
    {
        return $this->order_repo->updateOrderStatus($status, $id);
    }
}
