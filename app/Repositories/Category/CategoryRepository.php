<?php

namespace App\Repositories\Category;

use App\Models\Category;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function getCategory($params)
    {
        $per_page = $params['per_page'] ?? 10;

        $categories = $this->model;

        if (isset($params['keyword'])) {
            $categories = $categories->where('name', 'like', '%' . $params['keyword'] . '%');
        }

        return $categories->paginate($per_page);
    }
}
