<?php

namespace App\Repositories\Cart;

interface CartRepositoryInterface
{
    public function getCartByUserId($user_id);

    public function removeCartByUserId($user_id);
}
