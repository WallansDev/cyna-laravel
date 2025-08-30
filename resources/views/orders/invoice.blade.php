<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture Commande #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { margin-bottom: 30px; }
        .title { font-size: 22px; font-weight: bold; }
        .section { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #eee; }
        .total { font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Facture officielle</div>
        <div>Date : {{ date('d/m/Y') }}</div>
        <div>Commande n° : {{ $order->id }}</div>
    </div>
    <div class="section">
        <strong>Client :</strong><br>
        {{ $order->user->name }}<br>
        @if($order->billingAddress)
            {{ $order->billingAddress->full_address }}<br>
        @endif
    </div>
    <div class="section">
        <strong>Détail de la commande :</strong>
        <table>
            <thead>
                <tr>
                    <th>Service/Produit</th>
                    <th>Quantité</th>
                    <th>Prix unitaire (€)</th>
                    <th>Total (€)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->service_name ?? $item->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="total">Total TTC : {{ number_format($order->total, 2) }} €</div>
    </div>
    <div class="section">
        <strong>Merci pour votre commande !</strong>
    </div>
</body>
</html>
