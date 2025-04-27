<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>

    <h2>Invoice for Order : {{ $order->id }}</h2>
    <p><strong>Customer:</strong> {{ $order->user->name }}</p>
    <p><strong>Description :</strong> {{ $order->product->description }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('d M, Y') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Total:</strong> {{ $order->total }} Tk</p>

    <h4>Order Items:</h4>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price (Each)</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->price * $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
