<div class="container">
    <nav>
        <ul class="nav nav-pills">
            <li class="nav-item"><a class="nav-link active" href="/profile">Мой профиль</a></li>
            <li class="nav-item"><a class="nav-link" href="/cart">Корзина</a></li>
            <li class="nav-item"><a class="nav-link" href="/orderProduct">Мои заказы</a></li>
            <li class="nav-item"><a class="nav-link" href="/logout">Выйти</a></li>
        </ul>
    </nav>

    <h3 class="text-center my-4">Каталог</h3>
    <div class="product-table">
        @csrf

    @foreach ($products as $product)



        <div class="card text-center mb-4">
            <img class="card-img-top" src="{{ $product->image }} ; ?>"
                 alt="{{ $product->name }}">
            <div class="card-body">
                <h5 class="card-title">Название: {{ $product->name }}</h5>
                <p class="card-text">{{ $product->descroption }}</p>
            </div>
            <div class="card-footer">

               Цена: {{ $product->price }}

                <p id="amount-{{ $product->id }}">
                    В корзине: {{ isset($cartItems[$product->id]) ? $cartItems[$product->id]->amount : 0 }}
                </p>

                <div class="btn-group">

                    <form class="add-product" action="/add-product" method="POST" onsubmit="return false">
                        <input type="hidden" name="product_id" value="">
                        <input type="hidden" name="amount" value="1">
                        <button type="submit" class="btn btn-danger">+</button>


                    </form>
                    <form class="decrease-product" action="/decrease-product" method="POST"  onsubmit="return false">
                        <input type="hidden" name="product_id" value="">
                        <button type="submit" class="btn btn-danger">-</button>
                    </form>
                </div>
            </div>

            <form action="/product" method="POST">
                <input type="hidden" name="product_id" value="">
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
        height: 200px;
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
    $("document").ready(function() {
        $('.add-product').submit(function () {

            var form = $(this);
            $.ajax({
                type: 'POST',
                url: "/add-product",
                data: form.serialize(),
                dataType: 'json',
                success: function (data){
                    var obj = JSON.parse(data);
                    form.closest('.card-footer').find('.product-count').text(obj.count);
                }
            });
        });

        $('.decrease-product').submit(function () {

            var form = $(this);
            $.ajax({
                type: 'POST',
                url: "/decrease-product",
                data: form.serialize(),
                dataType: 'json',
                success: function (data){
                    var obj = JSON.parse(data);
                    form.closest('.card-footer').find('.product-count').text(obj.count);
                }
            });
        });
    });
</script>

