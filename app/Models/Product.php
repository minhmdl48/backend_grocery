<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';
    protected $date = ['deleted_at'];
    protected $guarded = [];

    public function usersWhoFavourited()
    {
        return $this->belongsToMany(User::class, 'product_favourites', 'product_id', 'user_id');
    }

    public function usersWhoReviewed()
    {
        return $this->belongsToMany(User::class, 'product_reviews', 'product_id', 'user_id');
    }

    public function favourites()
    {
        return $this->belongsToMany(User::class, 'product_favourites', 'product_id', 'user_id');
    }

    public static function findWithFavourite($id)
    {
        $user_id = Auth::id();
        $product = self::find($id);

        if (!$product) {
            return null;
        }

        $product->favourite = $product->favourites()->where('user_id', $user_id)->exists();

        return $product;
    }

}
