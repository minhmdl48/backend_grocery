<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Services\CartService;
use App\Services\OrderItemService;
use App\Services\OrderService;
use App\Services\VnPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct(
        protected VnPaymentService $vn_payment_service,
        protected OrderService $order_service,
        protected OrderItemService $order_item_service,
        protected CartService $cart_service
    ) {}

    public function createPayment(OrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $params = $request->validated();
            $create_order = $this->order_service->createOrder($params);
            if (isset($create_order)) {
                $create_item = $this->order_item_service->createOrderItem($params, $create_order['id']);
            }

            if (!$create_item) {
                DB::rollBack();
                return $this->responseFail([], "Có lỗi xảy ra, vui lòng thử lại!");
            }

            DB::commit();

            $orderInfo = "thanhtoansanpham";
            $create_url['url'] = $this->vn_payment_service->createPayment($params['total_amount'], $orderInfo, $create_order['transaction_id']);
            return $this->responseSuccess($create_url, "Thành Công!");
        } catch (\Exception $e) {
            return $this->responseFail([], $e->getMessage());
        }
    }

    public function callBack(Request $request)
    {
        $response = $this->vn_payment_service->validateResponse($request->all());
        if ($response == '00') {
            $request = $request->all();
            $this->order_service->updateOrderByTransaction($request['vnp_TxnRef']);
            $this->cart_service->deleteCart($request['user_id']);
            return $this->responseSuccess([], "Thanh toán thành công");
        } else {
            return $this->responseSuccess([], $response);
        }
    }
}
