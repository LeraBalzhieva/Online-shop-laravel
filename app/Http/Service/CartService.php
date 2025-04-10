<?php

namespace App\Http\Service;
use App\Models\User;
use App\Http\Requests\AddProductRequest;
use App\Models\Product;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCart(User $user)
    {
       return $user->userProducts()->with('product')->get();
    }

    public function addProduct(User $user, Product $product, int $amount = 1): bool
    {
        $userProduct = $user->UserProducts()->where('product_id, $product->id')->first();
        if ($userProduct) {
            $userProduct->increment('amount', $amount);
        } else {
            UserProduct::query()->create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'amount' => $amount
            ]);
        }
        return $this->getCart($user);

    }

    public function decreaseProduct(int $productId, int $amount): bool
    {

    }
}
