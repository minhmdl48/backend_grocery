<?php

namespace App\Services;

use App\Repositories\Product\ProductRepository;

class ProductService
{
    public function __construct(
        protected ProductRepository $product_repo
    ) {}

    public function createProduct($params)
    {
        return $this->product_repo->create($params);
    }

    public function getProductByCategory($params, $id)
    {
        return $this->product_repo->getProductByCategory($params, $id);
    }

    public function getProduct($params)
    {
        return $this->product_repo->getProduct($params);
    }

    public function find($id)
    {
        return $this->product_repo->find($id);
    }

    public function deleteProduct($id)
    {
        return $this->product_repo->delete($id);
    }

    public function getProductFavorite($params)
    {
        return $this->product_repo->getProductFavorite($params);
    }
}
