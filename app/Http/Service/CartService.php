<?php

namespace App\Http\Service;
use App\Models\User;
use App\Http\Requests\AddProductRequest;
use App\Models\Product;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;
/**
 * Сервис для работы с корзиной пользователя.
 */
class CartService
{
    /**
     *  Получает все товары пользователя в корзине.
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function getCart(User $user)
    {
        return $user->userProducts()->with('product')->get();
    }

    /**
     * Добавляет товар в корзину пользователя.
     * @param User $user
     * @param int $productId
     * @param int $amount
     * @return \Illuminate\Database\Eloquent\Collection Обновленная корзина
     */
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

    /**
     * Уменьшает количество товара в корзине пользователя.
     * @param User $user
     * @param int $productId
     * @param int $amount
     * @return \Illuminate\Database\Eloquent\Collection Обновлённая корзина
     * @throws \Exception Если товар не найден в корзине
     */
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
