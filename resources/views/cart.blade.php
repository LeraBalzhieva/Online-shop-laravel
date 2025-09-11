<div class="container">
    <ul>
        <li class="active"><a href="/profile">Мой профиль</a></li>
        <li class="active"><a href="/catalog">Каталог</a></li>
        <li class="active"><a href="/order">К оформлению</a></li>
    </ul>

    <h3>CART</h3>
    <div class="card-deck">
        @csrf

        @if(!empty($cartProducts))
        @php $totalAmount = 0; @endphp
        @foreach ($cartProducts as $cartProduct)
            <div class="card text">
                <img class="card-img-top" src="{{ $cartProduct->Product->image }}" alt="Card image">
                <div class="card-body">
                    <p class="card-text">{{ $cartProduct->Product->name }}</p>
                    <p class="card-text">Количество: {{ $cartProduct->amount }}</p>
                    <p class="card-text">Цена: {{ $cartProduct->Product->price  }} р</p>
                    <p class="card-text">Итого: {{ $cartProduct->amount * $cartProduct->Product->price }} р</p>

                     @php $totalAmount += $cartProduct->amount * $cartProduct->Product->price; @endphp
                </div>

            </div>

            @endforeach
            <h3 class="card-title">Общая сумма заказа: {{ $totalAmount }} руб.</h3>

        @else
            <p>Корзина пуста.</p>
        @endif
    </div>
</div>
    <style>
        body {
            font-style: sans-serif;
            text-align: -webkit-match-parent;
        }

        a {
            text-decoration: none;
        }

        h5 {
            font-size: 1.1em;
        }

        a:hover {
            text-decoration: none;
        }

        h3 {
            line-height: 5em;
        }

        .card-deck {
            display: flex; /* Используем flexbox для расположения карточек */
            flex-wrap: wrap; /* Позволяет карточкам переноситься на следующую строку */
            justify-content: space-between; /* Распределяем карточки по строке */
        }

        .card {
            flex: 0 1 calc(33.333% - 10px); /* Устанавливаем ширину карточки на 1/3 (33.333%) с отступом */
            margin-bottom: 20px; /* Отступ снизу для карточек */
            box-sizing: border-box; /* Учитываем отступы и границы в ширине */
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
        }

        .card:hover {
            box-shadow: 1px 2px 10px lightgray;
            transition: 0.3s;
        }

        .btn-group {
            display: flex; /* Используем flexbox для горизонтального расположения кнопок */
            justify-content: center; /* Центрируем кнопки */
            margin-top: 10px; /* Отступ сверху */
        }

        .btn {
            margin: 0 5px; /* Отступы между кнопками */
        }
    </style>
</div><?php
