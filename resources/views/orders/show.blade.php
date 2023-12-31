<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bot Factory</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    </head>
    <body>
        <h1>Order {{ $order->id }}</h1>

        <a href="{{ route('orders.index') }}" class="btn btn-primary">Home</a>
        
        <h2>Order Details</h2>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Total Weight</th>
                    <th>Robot Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->total_weight }}</td>
                    <td>{{ $order->robot_name }}</td>
                    <td><a href="{{ route('orders.edit', ['order' => $order->id]) }}">Edit Robot Name</a></td>
                </tr>
            </tbody>
        </table>

        <h2>Components</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Component ID</th>
                    <th>SKU</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Weight</th>
                    <th>Quanitity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->components as $component)
                    <tr>
                        <td>{{ $component->id }}</td>
                        <td>{{ $component->sku }}</td>
                        <td>{{ $component->description }}</td>
                        <td>{{ $component->category }}</td>
                        <td>{{ $component->weight }}</td>
                        <td>{{ $component->pivot->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </body>
</html>