<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\Update;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct(
        protected UserService $user_service,
        protected ProductService $product_service,
        protected CartService $cart_service,
        protected OrderService $order_service
    ) {}


    public function profile()
    {
        $id_user = Auth()->id();
        $user = $this->user_service->getUserLogin($id_user);
        return $this->responseSuccess($user, "Thành công!");
    }

    public function update(Update $request, $id)
    {
        try {
            if (!$id) {
                return $this->responseFail([], "User không tồn tại!", null, 404);
            }

            $params =  $request->validated();
            $user = $this->user_service->find($id);
            $user->update($params);
            return $this->responseSuccess($user, "Thành công!");
        } catch (\Exception $e) {
            return $this->responseFail([], $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $params = $request->all();
        $users = $this->user_service->getUserAll($params);

        $response = [
            'data' => $users->items(),
            'current_page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
            'per_page' => $users->perPage(),
            'total_items' => $users->total(),
        ];

        return $this->responseSuccess($response, "Thành công!");
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:6',
                'phone' => 'required|regex:/^0[0-9]{9,10}$/',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            $param_users = array_merge(
                $request->only(["email", "phone", 'user_name']),
                ['password' => bcrypt($request->password)]
            );

            $user = $this->user_service->createUser($param_users);

            return $this->responseSuccess($user, 'Tạo người dùng thành công!');
        } catch (\Exception $e) {
            return $this->responseFail([], $e->getMessage());
        }
    }

    public function delete($id)
    {
        if ($id) {
            $this->user_service->deleteUser($id);

            return $this->responseSuccess([], "Xóa thành công!");
        }

        return $this->responseFail([], "Xóa thất bại!");
    }

    public function edit($id)
    {
        $user = $this->user_service->find($id);
        if ($user)
            return $this->responseSuccess($user);

        return $this->responseFail([]);
    }

    public function favourite($id)
    {
        try {
            $user_id = Auth::id();
            $product = $this->product_service->find($id);

            if (!$product) {
                return $this->responseFail([], "Sản phẩm không tồn tại");
            }

            if (!$product->usersWhoFavourited()->where('user_id', $user_id)->exists()) {
                $product->usersWhoFavourited()->attach($user_id);
                return $this->responseSuccess([], "Đã thêm vào danh sách yêu thích!");
            }

            $product->usersWhoFavourited()->detach($user_id);
            return $this->responseSuccess([], "Đã xóa khỏi danh sách yêu thích!");
        } catch (\Exception $e) {
            return $this->responseFail([], "Có lỗi xảy ra: " . $e->getMessage());
        }
    }

    public function review(Request $request, $id)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:255',
            ], [
                'comment.required' => 'Vui lòng nhập bình luận.',
                'comment.string' => 'Bình luận phải là một chuỗi.',
                'comment.max' => 'Bình luận không được vượt quá 255 ký tự.',
            ]);

            $user_id = Auth::id();
            $comment = $request->comment;

            $product = $this->product_service->find($id);

            if (!$product) {
                return $this->responseFail([], "Sản phẩm không tồn tại");
            }

            $product->reviews()->create([
                'user_id' => $user_id,
                'comment' => $comment
            ]);

            return $this->responseSuccess([], "Thêm review thành công!");
        } catch (\Exception $e) {
            return $this->responseFail([], "Có lỗi xảy ra: " . $e->getMessage());
        }
    }

    public function cart(Request $request)
    {
        $user_id = Auth::id();
        $carts = $this->cart_service->getCartByUserId($user_id);
        return $this->responseSuccess($carts, "Thành công!");
    }

    public function orderHistory(Request $request)
    {
        $params = $request->all();
        $user_id = Auth::id();
        $history = $this->user_service->getHistoryByUser($user_id, $params);
        $response = [
            'data' => $history->items(),
            'current_page' => $history->currentPage(),
            'total_pages' => $history->lastPage(),
            'per_page' => $history->perPage(),
            'total_items' => $history->total(),
        ];

        return $this->responseSuccess($response, "Thành công!");
    }

    public function orderHistoryCms(Request $request)
    {
        $params = $request->all();
        $history = $this->user_service->getHistory($params);
        $response = [
            'data' => $history->items(),
            'current_page' => $history->currentPage(),
            'total_pages' => $history->lastPage(),
            'per_page' => $history->perPage(),
            'total_items' => $history->total(),
        ];

        return $this->responseSuccess($response, "Thành công!");
    }

    public function updateOrderStatus(Request $request)
    {
        $status = $request->status;
        $order_id = $request->order_id;
        if (!$status || !$order_id) return $this->responseFail([], "Có lỗi xảy ra vui lòng thử lại!");

        $this->order_service->updateOrderStatus($status, $order_id);
        return $this->responseSuccess([], "Cập nhật Order Status thành công!");
    }
}
