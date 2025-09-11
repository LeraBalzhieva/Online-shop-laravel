<div class="container">
    <nav>
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="/profile">Мой профиль</a></li>
            <li class="nav-item"><a class="nav-link" href="/cart">Корзина</a></li>
            <li class="nav-item"><a class="nav-link" href="/orderProducts">Мои заказы</a></li>
            <li class="nav-item"><a class="nav-link" href="/logout">Выйти</a></li>
        </ul>
    </nav>

    <h3 class="text-center my-4">Каталог</h3>
    <div class="product-table">
        @csrf

        @foreach ($products as $product)
        <div class="card text-center mb-4">
            <img class="card-img-top" src="{{ $product->image }}"
                 alt="{{$product->name}}">
            <div class="card-body">
                <h5 class="card-title">{{$product->name}}</h5>
                <p class="card-text">{{$product->description}}</p>
            </div>
            <div class="card-footer">
                <strong>Цена: {{$product->price}} р</strong>

                <p> Количество: <span class="product-count" data-product-id={{$product->id}} >
                {{$product->getAmountInCart(\Illuminate\Support\Facades\Auth::user())}}
                        </span>
                шт. </p>

                <div class="btn-group">
                    <form class="add-product" action="/add-product" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <input type="hidden" name="amount" value="1">
                        <button type="submit" class="btn btn-danger">+</button>
                    </form>
                    <form class="decrease-product" action="/decrease-product" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{$product->id}}">
                        <input type="hidden" name="amount" value="1">
                        <button type="submit" class="btn btn-danger">-</button>
                    </form>
                </div>
            </div>

            <form action="{{route('product.show',['product' => $product->id])}}" method="GET">
                @csrf
                <input type="hidden" name="product_id" value="{{$product->id}}">
                <button type="submit">Открыть продукт</button>
            </form>
        </div>
        @endforeach


    </div>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        color: #333;
    }

    nav {
        margin-bottom: 20px;
    }

    .nav-link {
        margin-right: 15px;
    }

    .product-table {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between; /* Распределяет пространство между карточками */
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        transition: box-shadow 0.3s;
        flex: 1 1 calc(33.333% - 20px); /* Устанавливает ширину карточек */
        margin: 10px; /* Отступы между карточками */
        box-sizing: border-box; /* Учитывает отступы в ширине карточки */
    }

    .card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .card-img-top {
        height: 250px;
        object-fit: cover;
    }

    .btn-group {
        display: flex;
        align-items: center; /* Центрирует кнопки и текст по вертикали */
        justify-content: center; /* Центрирует кнопки в группе */
        margin-top: 20px; /* Отступ сверху для кнопок */
    }

    .btn {
        width: 40px;
        height: 40px;
        font-size: 20px;
        padding: 0;
        margin: 0 5px; /* Отступы между кнопками */
    }

    .quantity {
        margin-left: 5px; /* Отступ между кнопкой и количеством */
        font-weight: bold; /* Выделение количества */
    }

    h3 {
        margin-top: 20px;
        margin-bottom: 20px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script>
    $(document).ready(function() {
        // Обработка добавления товара
        $('.add-product').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    // Обновляем количество на странице
                    var cardFooter = $(this).closest('.card-footer');
                    cardFooter.find('.product-count').text(response.count);

                }.bind(this),
                error: function(xhr) {
                    alert('Ошибка: ' + xhr.responseJSON.message);
                }
            });
        });

        // Обработка уменьшения количества товара
        $('.decrease-product').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    // Обновляем количество на странице
                    var cardFooter = $(this).closest('.card-footer');
                    cardFooter.find('.product-count').text(response.count);


                }.bind(this),
                error: function(xhr) {
                    alert('Ошибка: ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>

