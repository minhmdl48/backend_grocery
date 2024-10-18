<?php

namespace App\Services;

use App\Repositories\Banner\BannerRepository;

class BannerService
{
    public function __construct(
        protected BannerRepository $banner_repo
    ) {}

    public function getBanner()
    {
        return $this->banner_repo->getBannerRepo();
    }

    public function createBanner($params)
    {
        return $this->banner_repo->create($params);
    }

    public function getAll()
    {
        return $this->banner_repo->getAll()->count();
    }

    public function find($id)
    {
        return $this->banner_repo->find($id);
    }

    public function updateBanner($id, $params)
    {
        return $this->banner_repo->update($id, $params);
    }

    public function deleteBanner($id)
    {
        return $this->banner_repo->delete($id);
    }
}
