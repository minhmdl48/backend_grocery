<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\BaseRepository;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function updateOrderByTransaction($transaction_id)
    {
        return $this->model->where("transaction_id", "=", $transaction_id)->update(['payment_status' => 'completed']);
    }

    public function getHistoryByUser($user_id, $params)
    {
        $per_page = $params['per_page'] ?? 10;

        $result = $this->model->with('orderItems.product')->where("user_id", $user_id);

        return $result->paginate($per_page);
    }

    public function getHistory($params)
    {
        $per_page = $params['per_page'] ?? 10;

        $result = $this->model->with('orderItems.product');

        return $result->paginate($per_page);
    }

    public function updateOrderStatus($status, $id)
    {
        return $this->model->where("id", "=", $id)->update(['order_status' => $status]);
    }
}
