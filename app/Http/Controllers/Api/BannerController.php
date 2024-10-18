<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\Create;
use App\Http\Requests\Banner\Update;
use App\Services\BannerService;
use App\Services\UploadFileService;

class BannerController extends Controller
{
    public function __construct(
        protected BannerService $banner_service,
        protected UploadFileService $uploadfile_service
    ) {}

    public function index()
    {
        return $this->banner_service->getBanner();
    }

    public function create(Create $request)
    {
        try {
            $banners = $this->banner_service->getAll();

            if ($banners >= 5) {
                return $this->responseFail([], "The maximum number of banners is 5");
            }

            $params =  $request->validated();
            $file = $request->file('url');
            if ($request->hasFile('url')) {
                $folder = "banner/";
                $upload = $this->uploadfile_service->upload($file, $folder);
                $params['url'] = $upload['url'];
                $banner = $this->banner_service->createBanner($params);

                return $this->responseSuccess($banner, "Created banner successfully!");
            }

            return $this->responseFail();
        } catch (\Exception $e) {
            $this->uploadfile_service->destroy($upload['url'], $upload['file']);
            return $this->responseFail([], $e->getMessage());
        }
    }

    public function update(Update $request, $id)
    {
        try {
            $banner = $this->banner_service->find($id);
            if (!isset($banner)) {
                return $this->responseFail([], "Banner does not exist.");
            }

            $params =  $request->validated();

            $file = $request->file('url');
            if ($request->hasFile('url')) {
                if ($banner->url) {
                    $this->uploadfile_service->destroyImage($banner->url);
                }

                $folder = 'banner/';
                $upload = $this->uploadfile_service->upload($file, $folder);
                $params['url'] = $upload['url'];
            } else {
                // Nếu không có ảnh mới, giữ nguyên ảnh cũ
                $params['url'] = $banner->url;
            }

            $banner->update($params);
            return $this->responseSuccess($banner, "UPDATED SUCCESSFULLY");
        } catch (\Exception $e) {
            $this->uploadfile_service->destroy($upload['url'], $upload['file']);
            return $this->responseFail([], $e->getMessage());
        }
    }

    public function delete($id)
    {
        $banner = $this->banner_service->find($id);
        if (isset($banner)) {
            $this->uploadfile_service->destroyImage($banner->url);
            $this->banner_service->deleteBanner($id);

            return $this->responseSuccess([], "DELETED SUCCESSFULLY");
        }

        return $this->responseFail([], "DELETED FAILED");
    }

    public function edit($id)
    {
        $banner = $this->banner_service->find($id);
        if ($banner)
            return $this->responseSuccess($banner);

        return $this->responseFail([]);
    }
}
