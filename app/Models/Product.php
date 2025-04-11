<?php

namespace App\Models;

use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class  Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image'];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
    public function userProduct(User $user)
    {
        return $this->hasMany(UserProduct::class);
    }
    public function getAmountInCart(User $user)
    {
        return $this->userProduct($user)->first()->amount ?? 0;
    }


/*    public function orders()


    {
        return $this->hasMany(
            Order::class,
            OrderProduct::class,
            'product_id',
            'id',
            'id',
            'order_id',
        );
    }*/


}
