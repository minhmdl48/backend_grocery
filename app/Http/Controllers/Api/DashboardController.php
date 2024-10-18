<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OrderItemService;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected OrderItemService $order_item_service) {}

    public function topProductByMonth(Request $request)
    {
        $month_now = Carbon::now()->month;
        $year_now = Carbon::now()->year;

        $top_order_items = $this->order_item_service->getTopProduct($month_now, $year_now);

        $response = [
            'data' => $top_order_items->items(),
            'current_page' => $top_order_items->currentPage(),
            'total_pages' => $top_order_items->lastPage(),
            'per_page' => $top_order_items->perPage(),
            'total_items' => $top_order_items->total(),
        ];

        return $this->responseSuccess($response, "Thành công!");
    }
}
