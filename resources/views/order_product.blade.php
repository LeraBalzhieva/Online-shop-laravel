<form action="/orderProducts" method="POST">
    <div class="container">


        <li class="active"><a href="/catalog">Назад в каталог</a>
        <h3 class="text-center">Ваши заказы:</h3>

        @foreach($userOrders as $order)
                @php $totalAmount = 0; @endphp
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="card-title">Заказ номер: {{ $order->id }}</h2>
                    <p class="card-text"><strong>Имя:</strong> {{ $order->name }}</p>
                    <p class="card-text"><strong>Номер телефона:</strong> {{ $order->phone }}</p>
                    <p class="card-text"><strong>Город:</strong> {{ $order->city }}</p>
                    <p class="card-text"><strong>Адрес:</strong> {{ $order->address }}</p>
                    <p class="card-text"><strong>Комментарии:</strong> {{ $order->comment }}</p>

                    <h4 class="mt-4">Список продуктов:</h4>
                    @foreach($order->orderProducts as $orderProduct)
                        <div class="product-item mb-2">
                            <p class="card-text"><strong>Название продукта:</strong> {{ $orderProduct->product->name }}</p>
                            <p class="card-text"><strong>Цена:</strong> {{ number_format($orderProduct->product->price, 2, ',', ' ') }} р</p>
                            <p class="card-text"><strong>Количество:</strong> {{ $orderProduct->amount }} шт</p>


                        </div>
                    @endforeach

                    <hr>

                    <p class="font-weight-bold">
                        Общая сумма заказа:
                        <span class="total-price">{{ number_format($order->totalSum(), 2, ',', ' ') }} р</span>
                    </p>

                    </p>
                </div>
            </div>
        @endforeach


    </div>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        color: #333;
        padding: 20px;
    }

    .nav-pills .nav-link {
        margin-right: 15px;
        border-radius: 5px;
    }

    .nav-pills .nav-link.active {
        background-color: #007bff;
        color: white;
    }

    h3 {
        margin-bottom: 20px;
        text-align: center;

    }

    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s;
        background-color: white;
    }

    .card:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .product-item {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    .product-item:last-child {
        border-bottom: none; /* Убираем нижнюю границу у последнего элемента */
    }

    .font-weight-bold {
        font-weight: bold;
        font-size: 1.2em;
    }

    .total-price {
        color: #28a745; /* Зеленый цвет для общей суммы */
        font-size: 1.5em; /* Увеличенный размер шрифта */
    }
</style>
