<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

/**
 * Контроллер для работы с продуктами и отзывами.
 */
class ProductController
{
    /**
     *  Отображает каталог продуктов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */
    public function getCatalog()
    {
        $products = Cache::remember('products_all', 3600, function () {
            return Product::all();
        });

        return view('catalog', compact('products'));
    }

    /**
     * Отображает страницу продукта с отзывами.
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|object
     */

    public function getProductReviews(Product $product)
    {

        $reviews = $product->reviews;
        $averageRating = Review::averageRating($product->id);

        return view('product', [
            'product' => $product,
            'reviews' => $reviews,
            'averageRating' => $averageRating
        ]);
    }

    /**
     * Обновляет данные продукта и сбрасывает кэш.
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        Cache::forget('products_all');

        return redirect()->route('catalog')
            ->with('success', 'Продукт обновлён и кэш сброшен');
    }

    /**
     * Удаляет продукт и сбрасывает кэш.
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();
        Cache::forget('products_all');

        return redirect()->route('catalog')
            ->with('success', 'Продукт удалён и кэш сброшен');
    }

    /**
     * Добавляет отзыв для продукта.
     * @param AddReviewRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */

    public function addReviews(AddReviewRequest $request, Product $product)
    {
        $data = $request->validated();

        Review::query()->create([
            'product_id' =>$product->id,
            'user_id' => $request->user()->id,
            'comment' => $data['comment'],
            'rating' => $data['rating'],
        ]);

        return redirect()->route('product.show', ['product' => $product->id]);

    }
}
