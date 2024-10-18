<?php

namespace App\Services;

use App\Models\OrderItem;
use App\Repositories\OrderItem\OrderItemRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Support\Facades\DB;

class OrderItemService
{
    public function __construct(
        protected OrderItemRepository $order_item_repo,
        protected ProductRepository $product_repo
    ) {}

    public function createOrderItem($params, $order_id)
    {
        try {
            $data = [];
            foreach ($params['list_order_item'] as $item) {
                $product = $this->product_repo->find($item['product_id']);
                if (!$product) {
                    return false;
                }

                if ($product['quantity'] < $item['quantity']) {
                    throw new \Exception("Trong kho đã hết sản phẩm!");
                }

                $product->decrement('quantity', $item['quantity']);

                $data[] = [
                    'order_id' => $order_id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime()
                ];
            }


            OrderItem::insert($data);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getTopProduct($month_now, $year_now)
    {
        return $this->order_item_repo->getTopProduct($month_now, $year_now);
    }
}
