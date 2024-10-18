<?php

namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    public function getProductByCategory($params, $id);

    public function getProduct($params);

    public function getProductFavorite($params);
}
