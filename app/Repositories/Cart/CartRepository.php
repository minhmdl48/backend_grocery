<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Repositories\BaseRepository;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    public function __construct(Cart $model)
    {
        $this->model = $model;
    }

    public function getCartByUserId($user_id)
    {
        return $this->model->with('product')->select("*")->where("user_id", "=", $user_id)->get()->toArray();
    }

    public function removeCartByUserId($user_id)
    {
        return $this->model->select("*")->where("user_id", "=", $user_id)->delete();
    }
}
