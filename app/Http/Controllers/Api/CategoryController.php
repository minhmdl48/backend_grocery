<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\Create;
use App\Http\Requests\Category\Update;
use App\Services\CategoryService;
use App\Services\UploadFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $category_service,
        protected UploadFileService $uploadfile_service
    ) {}

    public function index(Request $request)
    {
        $params = $request->all();
        $categories = $this->category_service->getCategory($params);
        $response = [
            'data' => $categories->items(),
            'current_page' => $categories->currentPage(),
            'total_pages' => $categories->lastPage(),
            'per_page' => $categories->perPage(),
            'total_items' => $categories->total(),
        ];

        return $this->responseSuccess($response);
    }

    public function getAll()
    {
        return $this->category_service->getAll();
    }

    public function create(Create $request)
    {
        try {
            DB::beginTransaction();
            $params = $request->only(['name', 'image']);
            $file = $request->file('image');
            if ($request->hasFile('image')) {
                $folder = 'category/';
                $upload = $this->uploadfile_service->upload($file, $folder);
                $params["image"] = $upload['url'];

                $category = $this->category_service->createCategory($params);

                DB::commit();
                return $this->responseSuccess($category, 'Category created successfully');
            }

            return $this->responseFail([], 'Category Created Failed');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->uploadfile_service->destroy($upload['url'], $upload['file']);
            return $this->responseFail([], $e->getMessage());
        }
    }

    public function update(Update $request, $id)
    {
        try {
            $params = $request->only(['name', 'image']);
            $file = $request->file('image');
            $category = $this->category_service->find($id);
            if (!isset($category)) {
                return $this->responseFail([], "Category does not exist.");
            }

            $file = $request->file('image');
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ từ Cloudinary
                if ($category->image) {
                    $this->uploadfile_service->destroyImage($category->image);
                }
                $folder = 'category/';
                $upload = $this->uploadfile_service->upload($file, $folder);
                $params['image'] = $upload['url'];
            } else {
                $params['image'] = $category->image;
            }

            $category->update($params);
            DB::commit();

            return $this->responseSuccess($category, 'Category created successfully');
        } catch (\Exception $e) {
            // Rollback giao dịch nếu có lỗi
            DB::rollBack();
            $this->uploadfile_service->destroy($upload['url'], $upload['file']);

            return $this->responseFail([], $e->getMessage());
        }
    }

    public function delete($id)
    {
        $category = $this->category_service->find($id);
        if ($category) {
            $this->uploadfile_service->destroyImage($category->image);
            $this->category_service->deleteCategory($id);

            return $this->responseSuccess([], "Deleted Successfully");
        }

        return $this->responseFail([], "Deleted Failed");
    }

    public function edit($id)
    {
        $category = $this->category_service->find($id);
        if ($category)
            return $this->responseSuccess($category);

        return $this->responseFail([]);
    }
}
