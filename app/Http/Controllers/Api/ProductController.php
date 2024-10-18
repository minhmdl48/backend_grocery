<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\Create;
use App\Http\Requests\Product\Update;
use App\Models\Product;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\UploadFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(
        protected CategoryService $category_service,
        protected ProductService $product_service,
        protected UploadFileService $uploadfile_service,
        protected CartService $cart_service
    ) {}

    public function index(Request $request)
    {
        $params = $request->all();
        $product_by_category = $this->product_service->getProduct($params);
        $response = [
            'data' => $product_by_category->items(),
            'current_page' => $product_by_category->currentPage(),
            'total_pages' => $product_by_category->lastPage(),
            'per_page' => $product_by_category->perPage(),
            'total_items' => $product_by_category->total(),
        ];

        return $this->responseSuccess($response, "Thành công!");
    }

    public function create(Create $request)
    {
        try {
            DB::beginTransaction();
            $params = $request->validated();

            $category = $this->category_service->find($params['category_id']);
            if (!isset($category)) {
                return $this->responseFail([], "Category does not exist.");
            }

            $file = $request->file('image');
            if ($request->hasFile('image')) {
                $folder = 'product/';
                $upload = $this->uploadfile_service->upload($file, $folder);
                $params['image'] = $upload['url'];
                $product = $this->product_service->createProduct($params);

                DB::commit();
                return $this->responseSuccess($product, "Tạo mới thành công!");
            }

            return $this->responseFail([], "Tạo mới thất bại!");
        } catch (\Exception $e) {
            DB::rollBack();

            $this->uploadfile_service->destroy($upload['url'], $upload['file']);
            return $this->responseFail([], $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::findWithFavourite($id);

        if (!$product) {
            return $this->responseFail([], "Sản phẩm không tồn tại");
        }

        return $this->responseSuccess($product, "Lấy sản phẩm thành công!");
    }

    public function update(Update $request, $id)
    {
        try {
            DB::beginTransaction();
            $params = $request->validated();

            $product = $this->product_service->find($id);
            if (!isset($product)) {
                return $this->responseFail([], "Sản phẩm không tồn tại!");
            }

            $category = $this->category_service->find($params['category_id']);
            if (!isset($category)) {
                return $this->responseFail([], "Category does not exist.");
            }

            $file = $request->file('image');
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ từ Cloudinary
                if ($product->image) {
                    $this->uploadfile_service->destroyImage($product->image);
                }

                $folder = 'product/';
                $upload = $this->uploadfile_service->upload($file, $folder);
                $params['image'] = $upload['url'];
            } else {
                // Nếu không có ảnh mới, giữ nguyên ảnh cũ
                $params['image'] = $product->image;
            }

            $product->update($params);

            DB::commit();
            return $this->responseSuccess($product, "Cập nhật thành công!");
        } catch (\Exception $e) {
            // Rollback giao dịch nếu có lỗi
            DB::rollBack();
            $this->uploadfile_service->destroy($upload['url'], $upload['file']);

            return $this->responseFail([], $e->getMessage());
        }
    }

    public function delete($id)
    {
        $product = $this->product_service->find($id);
        if (isset($product)) {
            $this->uploadfile_service->destroyImage($product->image);
            $this->product_service->deleteProduct($id);

            return $this->responseSuccess([], "Xóa thành công!");
        }

        return $this->responseFail([], "Xóa thất bại!");
    }

    public function productByCategory(Request $request, $id)
    {
        $params = $request->all();
        $category = $this->category_service->find($id);
        if (!isset($category)) {
            return $this->responseFail([], "Category does not exist.");
        }

        $product_by_category = $this->product_service->getProductByCategory($params, $id);
        $response = [
            'data' => $product_by_category->items(),
            'current_page' => $product_by_category->currentPage(),
            'total_pages' => $product_by_category->lastPage(),
            'per_page' => $product_by_category->perPage(),
            'total_items' => $product_by_category->total(),
        ];

        return $this->responseSuccess($response, "Thành công!");
    }

    public function listFavorite(Request $request)
    {
        $params = $request->all();
        $products = $this->product_service->getProductFavorite($params);

        $response = [
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'total_pages' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total_items' => $products->total(),
        ];

        return $this->responseSuccess($response, "Successfully!");
    }

    public function cart(Request $request)
    {
        try {
            DB::beginTransaction();
            $params = $request->all();
            $create_cart = $this->cart_service->createCart($params);

            if (!$create_cart) {
                DB::rollBack();
                return $this->responseFail([], "Cập nhật giỏ hàng thất bại!");
            }

            DB::commit();
            return $this->responseSuccess([], "Cập nhật giỏ hàng thành công!");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFail([], $e->getMessage());
        }
    }
}
