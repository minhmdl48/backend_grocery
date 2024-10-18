<?php

namespace App\Repositories\OrderItem;

interface OrderItemRepositoryInterface
{
    public function getTopProduct($month_now, $year_now);
}
