<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #1a202c;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background-color: #f8f9fa;
        }

        .payment-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #38a169;
        }

        a {
            texte-decoration: none;
        }

        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #3182ce;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3182ce;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }

        .success {
            color: #38a169;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Confirmation de paiement</h1>
    </div>

    <div class="content">
        <p>Bonjour {{ $user->surname }},</p>

        @if ($customMessage)
            <p>{{ $customMessage }}</p>
        @else
            <p class="success">✅ Votre paiement a été traité avec succès !</p>
        @endif

        <div class="payment-details">
            <h3>Détails du paiement</h3>
            @if ($payment)
                <p><strong>ID Transaction :</strong> {{ $payment->stripe_payment_id }}</p>
                <p><strong>Montant :</strong> {{ number_format($payment->amount / 100, 2) }} €</p>
                <p><strong>Date :</strong> {{ $payment->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Statut :</strong>
                    @if ($payment->status == 'succeeded')
                        {{ 'validé' }}
                    @else
                        {{ 'non défini' }}
                    @endif
                </p>
            @else
                <p>Paiement traité avec succès</p>
            @endif
        </div>

        <div class="order-details">
            <h3>Détails de la commande #{{ $order->id }}</h3>
            <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            @if ($order->stripePayment && $order->stripePayment->applied_coupon_code)
                <p><b>Coupon :</b>
                    {{ $order->stripePayment->applied_coupon_code }}
                    (−{{ number_format($order->stripePayment->discount_amount ?? 0, 2) }} €)
                </p>
            @endif
            @if ($order->items && $order->items->count())
                <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
                    <thead>
                        <tr style="background-color:#edf2f7; text-align:left;">
                            <th style="border-bottom:1px solid #e2e8f0;">Article</th>
                            <th style="border-bottom:1px solid #e2e8f0;">Quantité</th>
                            <th style="border-bottom:1px solid #e2e8f0;">Prix unitaire</th>
                            <th style="border-bottom:1px solid #e2e8f0;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td style="border-bottom:1px solid #f1f5f9;">
                                    {{ $item->service_name }}
                                    @if (!empty($item->price_type))
                                        <div style="color:#718096; font-size:12px;">Type: {{ $item->price_type }}</div>
                                    @endif
                                </td>
                                <td style="border-bottom:1px solid #f1f5f9;">{{ $item->quantity }}</td>
                                <td style="border-bottom:1px solid #f1f5f9;">{{ number_format($item->price, 2) }} €
                                </td>
                                <td style="border-bottom:1px solid #f1f5f9;">
                                    {{ number_format($item->price * $item->quantity, 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Aucun article associé à cette commande.</p>
            @endif
        </div>

        <p>Votre commande va maintenant être traitée par notre équipe.</p>

        <a href="{{ env('APP_URL') . '/orders' }}" style="color:white;" class="btn">Voir ma commande</a>
    </div>

    <div class="footer">
        <p>Merci de votre confiance,<br>L'équipe Cyna</p>
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>

</html>
