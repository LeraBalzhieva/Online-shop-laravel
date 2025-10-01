<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReviewRequest;
use App\Models\Product;
use App\Models\Review;
use App\Models\UserProduct;
use Illuminate\Support\Facades\Auth;


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
