<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class ProductController
{
    public function getCatalog()
    {
        $products = Product::all();
        $cartItems = UserProduct::query()->where('user_id', Auth::id())->get();

        return view('catalog', compact('products'));
    }

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
    public function index()
    {
        $products = Cache::remember('products_all', 3600, function () {
            return Product::all();

        });

        return view('catalog', compact('products'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        Cache::forget('products_all');

        return redirect()->route('catalog')
            ->with('success', 'Продукт обновлён и кэш сброшен');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        Cache::forget('products_all');

        return redirect()->route('catalog')
            ->with('success', 'Продукт удалён и кэш сброшен');
    }


    public function addReviews(AddReviewRequest $request, Product $product)
    {
        $data = $request->validated();

        Review::query()->create([
            'product_id' => $data['product_id'],
            'user_id' => Auth::id(),
            'comment' => $data['comment'],
            'rating' => $data['rating'],
        ]);

        return redirect()->route('product.show', ['product' => $data['product_id']]);

    }
}
