<?php

namespace App\Repositories\Banner;

use App\Models\Banner;
use App\Repositories\BaseRepository;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    public function __construct(Banner $model)
    {
        $this->model = $model;
    }

    public function getBannerRepo()
    {
        return $this->model->orderBy('updated_at', 'desc')->limit(5)->get();
    }
}
