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

    public function addProduct(User $user, int $productId, int $amount = 1)
    {
        $product = Product::findOrFail($productId);
        $userProduct = $user->userProducts()->where('product_id', $product->id)->first();

        if ($userProduct) {
            $userProduct->increment('amount', $amount);
        } else {
            UserProduct::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'amount' => $amount
            ]);
        }

        return $this->getCart($user);
    }

    public function decreaseProduct(User $user, int $productId, int $amount = 1)
    {
        $userProduct = $user->userProducts()->where('product_id', $productId)->firstOrFail();

        if (!$userProduct) {
            throw new \Exception('Product not found in cart');
        }

        if ($userProduct->amount > $amount) {
            $userProduct->decrement('amount', $amount);
        } else {
            $userProduct->delete();
        }

        return $this->getCart($user);
    }
}
