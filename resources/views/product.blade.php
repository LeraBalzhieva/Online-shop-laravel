<a href="/catalog">Назад в каталог</a>

<div class="product-info">
    <h2>О продукте</h2>
    <p>Название продукта: {{ $product->name }}</p>
    <p>Описание: {{ $product->description }}</p>
    <p>Цена: {{ $product->price }} р.</p>
    <p>Средняя оценка: {{ number_format($averageRating, 1) }}</p>
</div>

<h1>Отзывы о продукте</h1>

@if($reviews->count() > 0)
    @foreach($reviews as $review)
        <div class="review">
            <p>Оценка: {{ $review->rating }}/5</p>
            <p>Автор: {{ $review->user->name }}</p>
            <p>Дата: {{ $review->created_at->format('d.m.Y H:i') }}</p>
            <p>Комментарий: {{ $review->comment }}</p>
            <hr>
        </div>
    @endforeach
@else
    <p>Отзывов пока нет.</p>
@endif

@auth
    <h2>Оставить отзыв</h2>

    <form method="post" action="{{ route('review.store') }}">


        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <label for="rating">Оценка:</label>
        <select name="rating" id="rating" required>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>

        @error('rating')
        <div class="error">{{ $message }}</div>
        @enderror

        <label for="comment">Отзыв:</label>
        <textarea name="comment" id="comment" required></textarea>

        @error('comment')
        <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Отправить</button>
    </form>
@else
    <p><a href="{{ route('login') }}">Войдите</a>, чтобы оставить отзыв</p>
@endauth
