<!DOCTYPE html>
<html>

<head>
    <title>Receipt</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .center {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="center">
        <h2>{{ config('settings.app_name') }}</h2>
        <p>Phone: {{config('settings.phone')}} | Email: {{config('settings.email')}}</p>
        <p><strong>Receipt #: {{ $order->id }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price / $item->quantity, 2) }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @php
        $subtotal = $order->items->sum('price');
        $vat = $subtotal * 0.16;
        $total = $subtotal + $vat;
    @endphp

    <div style="margin-top: 20px;">
        <p>Sub-total: KES {{ number_format($subtotal, 2) }}</p>
        <p>VAT (16%): KES {{ number_format($vat, 2) }}</p>
        <p><strong>Total: KES {{ number_format($total, 2) }}</strong></p>
    </div>

    <p style="margin-top: 30px;">Served by: {{ $serving_user }}</p>
</body>

</html>
