<?php

namespace App\Services;

use App\Models\Cart;
use App\Repositories\Cart\CartRepository;

class CartService
{
    public function __construct(
        protected CartRepository $cart_repo,
    ) {}

    public function createCart($params)
    {
        try {
            $user_id = Auth()->id();
            $cart_repo = $this->cart_repo->getCartByUserId($user_id);
            if (!empty($cart_repo)) {
                $this->cart_repo->removeCartByUserId($user_id);
            }

            $carts = [];
            if (!empty($params["cart"])) {
                foreach ($params["cart"] as $key => $item) {
                    $carts[] = $item;
                    $carts[$key]["user_id"] = $user_id;
                    $carts[$key]['created_at'] = new \DateTime();
                    $carts[$key]['updated_at'] = new \DateTime();
                }
            }

            Cart::insert($carts);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function deleteCart($user_id)
    {
        return $this->cart_repo->removeCartByUserId($user_id);
    }

    public function getCartByUserId($user_id)
    {
        return $this->cart_repo->getCartByUserId($user_id);
    }
}
