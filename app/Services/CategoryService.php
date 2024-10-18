<?php

namespace App\Services;

use App\Repositories\Category\CategoryRepository;

class CategoryService
{
    public function __construct(protected CategoryRepository $category_repo) {}

    public function createCategory($params)
    {
        return $this->category_repo->create($params);
    }

    public function getAll()
    {
        return $this->category_repo->getAll();
    }

    public function getCategory($params)
    {
        return $this->category_repo->getCategory($params);
    }

    public function find($id)
    {
        return $this->category_repo->find($id);
    }

    public function updateCategory($params, $id)
    {
        return $this->category_repo->update($id, $params);
    }

    public function deleteCategory($id)
    {
        return $this->category_repo->delete($id);
    }
}
