<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Druk zamówień - status {{ $statusId }}</title>
    <style>
        @page {
            size: A4;
            margin: 0.5cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .order-card {
            page-break-after: always;
            height: 100vh;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }
        .order-card.compact {
            page-break-after: auto;
            height: auto;
            min-height: 100vh;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
        }
        .order-number {
            font-weight: bold;
            font-size: 14px;
        }
        .barcode {
            font-family: 'Libre Barcode 128', cursive;
            font-size: 30px;
            margin-top: -5px;
        }
        .customer-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .detail-box {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #f9f9f9;
        }
        .detail-title {
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 12px;
        }
        .products-container {
            flex-grow: 1;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .products-table th {
            background-color: #eee;
            text-align: left;
            padding: 4px;
            border: 1px solid #ddd;
        }
        .products-table td {
            padding: 4px;
            border: 1px solid #ddd;
        }
        .order-summary {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .summary-box {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .comments {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background-color: #f0f7ff;
            margin-bottom: 10px;
            white-space: pre-wrap;
        }
        .comment-line {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #ccc;
        }
        .comment-line:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .footer {
            font-size: 9px;
            color: #666;
            text-align: center;
            padding-top: 5px;
            border-top: 1px solid #ddd;
        }
        .no-print {
            margin-bottom: 15px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
        
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
</head>
<body>
    <div class="no-print">
        <h1>Druk zamówień - status {{ $statusId }}</h1>
        <p>Liczba zamówień: {{ count($blOrders) }}</p>
        <button onclick="window.print()">Drukuj wszystkie</button>
        <hr>
    </div>

    @foreach($blOrders as $index => $order)
        @php
            $productCount = count($order['products']);
            $isCompact = $productCount <= 10;
            
            // Formatowanie uwag
            $userComments = $order['user_comments'] ?? '';
            $userCommentsLines = array_filter(array_map('trim', explode("\n", str_replace('<br>', "\n", $userComments))));
            
            $adminComments = $order['admin_comments'] ?? '';
            $adminCommentsLines = array_filter(array_map('trim', explode("\n", str_replace('<br>', "\n", $adminComments))));

            // Wyszukaj numer zamówienia w komentarzu (D25 + 6 cyfr)
            $orderNumber = null;
            if (preg_match('/D25\d{2}[A-Za-z]\d{3}/', $adminComments, $matches)) {
                $orderNumber = $matches[0];
            }

            // Pobierz parametry produktów jeśli znaleziono numer zamówienia
            $productParams = [];
            if ($orderNumber) {
                try {
                    $orderModel = \App\Models\Order::where('number', $orderNumber)->first();
                    if ($orderModel && $orderModel->cart) {
                        $cartData = json_decode($orderModel->cart, true);
                        if (is_array($cartData)) {
                            $productParams = $cartData;
                        }
                    }
                } catch (\Exception $e) {
                    // Obsłuż błąd jeśli coś pójdzie nie tak
                }
            }
        @endphp
        {{dd($productParams)}}
        <div class="order-card {{ $isCompact ? 'compact' : '' }}">
            <div class="order-header">
                <div>
                    <div class="order-number">Zamówienie #{{ $index + 1 }}/{{ count($blOrders) }} | ID: {{ $order['order_id'] }}</div>
                    <div>Data: {{ date('d.m.Y H:i', $order['date_add']) }} | Źródło: {{ $order['order_source'] }}</div>
                    <div>Status: {{ $order['order_status_id'] }} | Zewn. ID: {{ $order['external_order_id'] ?? '-' }}</div>
                </div>
                <div class="barcode">*{{ $order['order_id'] }}*</div>
            </div>

            <div class="customer-details">
                <div class="detail-box">
                    <div class="detail-title">DANE KLIENTA</div>
                    <div><strong>Email:</strong> {{ $order['email'] ?? 'brak' }}</div>
                    <div><strong>Tel:</strong> {{ $order['phone'] ?? 'brak' }}</div>
                    @if($order['user_login'])
                        <div><strong>Login:</strong> {{ $order['user_login'] }}</div>
                    @endif
                </div>

                <div class="detail-box">
                    <div class="detail-title">DOSTAWA</div>
                    <div><strong>Sposób:</strong> {{ $order['delivery_method'] }}</div>
                    <div><strong>Koszt:</strong> {{ number_format($order['delivery_price'], 2, ',', ' ') }} zł</div>
                    @if($order['delivery_fullname'])
                        <div><strong>Odbiorca:</strong> {{ $order['delivery_fullname'] }}</div>
                    @endif
                    @if($order['delivery_address'])
                        <div><strong>Adres:</strong> {{ $order['delivery_address'] }}, {{ $order['delivery_postcode'] }} {{ $order['delivery_city'] }}</div>
                    @endif
                </div>

                <div class="detail-box">
                    <div class="detail-title">PŁATNOŚĆ</div>
                    <div><strong>Metoda:</strong> {{ $order['payment_method'] }}</div>
                    <div><strong>Status:</strong> {{ $order['payment_method_cod'] == "1" ? 'Pobranie' : 'Opłacone' }}</div>
                    <div><strong>Kwota:</strong> {{ number_format($order['payment_done'], 2, ',', ' ') }} zł</div>
                </div>

                <div class="detail-box">
                    <div class="detail-title">FAKTURA</div>
                    @if($order['want_invoice'] == "1")
                        @if($order['invoice_company'])
                            <div><strong>Firma:</strong> {{ $order['invoice_company'] }}</div>
                            <div><strong>NIP:</strong> {{ $order['invoice_nip'] }}</div>
                        @else
                            <div><strong>Osoba:</strong> {{ $order['invoice_fullname'] }}</div>
                        @endif
                        <div><strong>Adres:</strong> {{ $order['invoice_address'] }}, {{ $order['invoice_postcode'] }} {{ $order['invoice_city'] }}</div>
                    @else
                        <div>Brak faktury</div>
                    @endif
                </div>
            </div>

            <div class="products-container">
                <table class="products-table">
                    <thead>
                        <tr>
                            <th width="5%">LP</th>
                            <th width="35%">Produkt</th>
                            <th width="20%">Wariant</th>
                            <th width="10%">Ilość</th>
                            <th width="15%">Cena</th>
                            <th width="15%">Wartość</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order['products'] as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $product['name'] }}
                                    @if(isset($productParams[$loop->index]))
                                        <div class="product-params">
                                            @foreach($productParams[$loop->index] as $key => $value)
                                                @if(!in_array($key, ['productId', 'quantity']))
                                                    <div class="param-row">
                                                        <span class="param-name">{{ ucfirst($key) }}:</span>
                                                        <span class="param-value">{{ $value }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $product['variant'] ?? '-' }}</td>
                                <td>{{ $product['quantity'] }}</td>
                                <td>{{ number_format($product['price_brutto'], 2, ',', ' ') }} zł</td>
                                <td>{{ number_format($product['price_brutto'] * $product['quantity'], 2, ',', ' ') }} zł</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="order-summary">
                <div class="summary-box">
                    <div class="detail-title">PODSUMOWANIE</div>
                    <div><strong>Wartość produktów:</strong> {{ number_format($order['payment_done'] - $order['delivery_price'], 2, ',', ' ') }} zł</div>
                    <div><strong>Koszt dostawy:</strong> {{ number_format($order['delivery_price'], 2, ',', ' ') }} zł</div>
                    <div><strong>RAZEM DO ZAPŁATY:</strong> {{ number_format($order['payment_done'], 2, ',', ' ') }} zł</div>
                </div>

                @if(!empty($userCommentsLines))
                    <div class="comments">
                        <div class="detail-title">UWAGI KLIENTA</div>
                        @foreach($userCommentsLines as $line)
                            <div class="comment-line">{{ $line }}</div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(!empty($adminCommentsLines))
                <div class="comments">
                    <div class="detail-title">NOTATKI WEWNĘTRZNE</div>
                    @foreach($adminCommentsLines as $line)
                        <div class="comment-line">{{ $line }}</div>
                    @endforeach
                </div>
            @endif

            <div class="footer">
                ZK: {{ $order['extra_field_1'] ?? 'brak' }} | 
                PA: {{ $order['extra_field_2'] ?? 'brak' }} | 
                Data potwierdzenia: {{ date('d.m.Y H:i', $order['date_confirmed']) }} | 
                ID: {{ $order['order_id'] }}
            </div>
        </div>
    @endforeach

    <script>
        window.onload = function() {
            @if(count($blOrders) > 0)
                if({{ count($blOrders) }} === 1) {
                    window.print();
                }
            @endif
        };
    </script>
</body>
</html>