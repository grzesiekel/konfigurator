@php
    // Zbierz wszystkie unikalne klucze z każdego elementu
    $allKeys = collect($cartData)
        ->flatMap(fn($item) => array_keys($item))
        ->unique()
        ->filter(fn($key) => $key !== 'productId') // usuń productId
        ->values(); // resetuje indeksy
@endphp

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Karta Zamówienia #{{ $order->number }}</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        .order-info {
            margin-bottom: 30px;
        }

        .order-info h2 {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }

        th.quantity, td.quantity {
            width: 80px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="order-info">
        <h2>Zamówienie #{{ $order->number }}</h2>
        <p><strong>Imię i nazwisko:</strong> {{ $order->name }}</p>
        <p><strong>Email:</strong> {{ $order->email }}</p>
    </div>

    <table>
       <thead>
    <tr>
        <th>#</th>
        @foreach ($allKeys as $key)
            @php
                $label = $key === 'quantity' ? 'Ilość' : ucfirst($key);
            @endphp
            <th class="{{ $key === 'quantity' ? 'quantity' : '' }}">{{ $label }}</th>
        @endforeach
    </tr>
</thead>
        <tbody>
            @foreach ($cartData as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    @foreach ($allKeys as $key)
                        <td class="{{ $key === 'quantity' ? 'quantity' : '' }}">
                            {{ $item[$key] ?? '-' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
