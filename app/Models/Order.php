<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'city', 'address', 'comment'];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function totalSum()
    {
        $items = $this->orderProducts()->with('product')->get();

        $total = 0;

        foreach ($items as $item) {
            $total += $item->product->price * $item->amount;
        }

        return $total;
    }



}
