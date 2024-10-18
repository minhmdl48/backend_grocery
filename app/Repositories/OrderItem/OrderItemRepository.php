<?php

namespace App\Repositories\OrderItem;

use App\Models\OrderItem;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class OrderItemRepository extends BaseRepository implements OrderItemRepositoryInterface
{
    public function __construct(OrderItem $model)
    {
        $this->model = $model;
    }

    public function getTopProduct($month_now, $year_now)
    {
        $per_page = $params['per_page'] ?? 10;

        $result = $this->model->join('orders', 'order_item.order_id', '=', 'orders.id')
            ->join('products', 'order_item.product_id', '=', 'products.id')
            ->select('products.*', DB::raw('SUM(order_item.quantity) as total_quantity'))
            ->whereMonth('orders.created_at', $month_now)
            ->whereYear('orders.created_at', $year_now)
            ->groupBy('products.id')
            ->orderByDesc('total_quantity');

        return $result->paginate($per_page);
    }
}
