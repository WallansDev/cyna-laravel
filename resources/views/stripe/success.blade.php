@extends('layouts.base')

@section('title', 'Paiement réussi - ' . config('app.name'))

@section('head-content')
    <style>
        .success-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 3rem 2rem;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2.5rem;
        }

        .success-title {
            color: #28a745;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .amount-display {
            font-size: 1.5rem;
            color: #007bff;
            font-weight: 600;
            margin: 1.5rem 0;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .payment-details {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
            text-align: left;
        }

        .payment-details h5 {
            color: #495057;
            margin-bottom: 1rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #6c757d;
        }

        .detail-value {
            color: #495057;
        }

        .action-buttons {
            margin-top: 2rem;
        }

        .btn-home {
            background: #007bff;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            background: #0056b3;
            color: white;
            transform: translateY(-2px);
        }

        .btn-receipt {
            background: #28a745;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-receipt:hover {
            background: #1e7e34;
            color: white;
            transform: translateY(-2px);
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>

            <h1 class="success-title">Paiement réussi !</h1>
            <p class="lead">Votre transaction a été traitée avec succès.</p>

            <div class="amount-display">
                <i class="fas fa-euro-sign me-2"></i>
                {{ number_format($amount, 2) }} EUR
            </div>

            <div class="payment-details">
                <h5><i class="fas fa-info-circle me-2"></i>Détails de la transaction</h5>

                <div class="detail-row">
                    <span class="detail-label">ID de transaction :</span>
                    <span class="detail-value">{{ $paymentIntent->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Statut :</span>
                    <span class="detail-value">
                        <span class="badge bg-success">Payé</span>
                    </span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Date :</span>
                    <span
                        class="detail-value">{{ \Carbon\Carbon::createFromTimestamp($paymentIntent->created)->format('d/m/Y H:i') }}</span>
                </div>

                @if (isset($paymentIntent->metadata->order_id))
                    <div class="detail-row">
                        <span class="detail-label">Numéro de commande :</span>
                        <span class="detail-value">{{ $paymentIntent->metadata->order_id }}</span>
                    </div>
                @endif
            </div>

            <div class="alert alert-info">
                <i class="fas fa-envelope me-2"></i>
                Un email de confirmation vous sera envoyé dans les prochaines minutes.
            </div>

            <div class="action-buttons">
                <a href="{{ route('home') }}" class="btn-home">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>

                <a href="#" class="btn-receipt" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Imprimer le reçu
                </a>
            </div>
        </div>
    </div>

    <script>
        // Ajouter un délai avant de rediriger automatiquement (optionnel)
        setTimeout(function() {
            // Vous pouvez rediriger automatiquement vers l'accueil après 30 secondes
            // window.location.href = '{{ route('home') }}';
        }, 30000);
    </script>
@endsection
