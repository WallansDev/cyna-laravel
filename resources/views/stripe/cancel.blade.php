@extends('layouts.base')

@section('title', 'Paiement échoué - ' . config('app.name'))

@section('head-content')
    <style>
        .error-container {
            max-width: 600px;
            margin: 3rem auto;
            padding: 3rem 2rem;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .error-icon {
            width: 80px;
            height: 80px;
            background: #dc3545;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2.5rem;
        }

        .error-title {
            color: #dc3545;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            border: 1px solid #f5c6cb;
        }

        .action-buttons {
            margin-top: 2rem;
        }

        .btn-retry {
            background: #007bff;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-retry:hover {
            background: #0056b3;
            color: white;
            transform: translateY(-2px);
        }

        .btn-home {
            background: #6c757d;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            background: #545b62;
            color: white;
            transform: translateY(-2px);
        }

        .help-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 2rem 0;
            text-align: left;
        }

        .help-section h5 {
            color: #495057;
            margin-bottom: 1rem;
        }

        .help-list {
            list-style: none;
            padding: 0;
        }

        .help-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .help-list li:last-child {
            border-bottom: none;
        }

        .help-list li i {
            color: #007bff;
            margin-right: 0.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="error-container">
            <div class="error-icon">
                <i class="fas fa-times"></i>
            </div>

            <h1 class="error-title">Paiement échoué</h1>
            <p class="lead">Nous n'avons pas pu traiter votre paiement.</p>

            @if (isset($error))
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $error }}
                </div>
            @endif

            <div class="help-section">
                <h5><i class="fas fa-question-circle me-2"></i>Que pouvez-vous faire ?</h5>
                <ul class="help-list">
                    <li>
                        <i class="fas fa-credit-card"></i>
                        Vérifiez que vos informations de carte sont correctes
                    </li>
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        Assurez-vous que votre banque autorise les paiements en ligne
                    </li>
                    <li>
                        <i class="fas fa-wifi"></i>
                        Vérifiez votre connexion internet
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        Contactez votre banque si le problème persiste
                    </li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="{{ route('stripe.checkout') }}" class="btn-retry">
                    <i class="fas fa-redo me-2"></i>Réessayer
                </a>

                <a href="{{ route('home') }}" class="btn-home">
                    <i class="fas fa-home me-2"></i>Retour à l'accueil
                </a>
            </div>

            <div class="mt-4">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Si le problème persiste, contactez notre support client.
                </small>
            </div>
        </div>
    </div>

    <script>
        // Rediriger automatiquement vers la page de paiement après 10 secondes
        setTimeout(function() {
            window.location.href = '{{ route('stripe.checkout') }}';
        }, 10000);
    </script>
@endsection
