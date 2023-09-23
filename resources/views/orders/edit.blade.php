<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bot Factory</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    </head>
    <body>
        <div class="container">
            <h1>Edit Robot Name</h1>

            <form method="POST" action="{{ route('orders.update', ['order' => $order->id]) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="robot_name">New Robot Name:</label>
                    <input type="text" name="robot_name" id="robot_name" class="form-control" value="{{ $order->robot_name }}">
                </div>

                <button type="submit" class="btn btn-primary">Update Robot Name</button>
            </form>
        </div>
    </body>
</html>
