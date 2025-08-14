@extends('layouts.base')

@section('title', 'Test Stripe - ' . config('app.name'))

@section('head-content')
    <style>
        .test-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .test-cards {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1.5rem 0;
        }

        .test-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin: 0.5rem 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .test-card:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .test-card.success {
            border-left: 4px solid #28a745;
        }

        .test-card.error {
            border-left: 4px solid #dc3545;
        }

        .test-card.secure {
            border-left: 4px solid #ffc107;
        }

        .card-number {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #495057;
        }

        .card-description {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .copy-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
            margin-left: 0.5rem;
        }

        .copy-btn:hover {
            background: #0056b3;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="test-container">
            <h1 class="text-center mb-4">
                <i class="fas fa-credit-card text-primary me-2"></i>
                Test Stripe - Cartes de test
            </h1>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Instructions :</strong> Cliquez sur une carte pour copier son numéro, puis utilisez-la dans le
                formulaire de paiement.
            </div>

            <div class="test-cards">
                <h4><i class="fas fa-check-circle text-success me-2"></i>Paiements réussis</h4>

                <div class="test-card success" onclick="copyCard('4242 4242 4242 4242')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="card-number">4242 4242 4242 4242</div>
                            <div class="card-description">Paiement standard réussi</div>
                        </div>
                        <button class="copy-btn">Copier</button>
                    </div>
                </div>

                <div class="test-card success" onclick="copyCard('4000 0000 0000 3220')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="card-number">4000 0000 0000 3220</div>
                            <div class="card-description">Paiement avec authentification 3D Secure réussie</div>
                        </div>
                        <button class="copy-btn">Copier</button>
                    </div>
                </div>
            </div>

            <div class="test-cards">
                <h4><i class="fas fa-times-circle text-danger me-2"></i>Paiements échoués</h4>

                <div class="test-card error" onclick="copyCard('4000 0000 0000 0002')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="card-number">4000 0000 0000 0002</div>
                            <div class="card-description">Carte refusée (générique)</div>
                        </div>
                        <button class="copy-btn">Copier</button>
                    </div>
                </div>

                <div class="test-card error" onclick="copyCard('4000 0000 0000 0069')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="card-number">4000 0000 0000 0069</div>
                            <div class="card-description">Carte expirée</div>
                        </div>
                        <button class="copy-btn">Copier</button>
                    </div>
                </div>

                <div class="test-card error" onclick="copyCard('4000 0000 0000 9995')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="card-number">4000 0000 0000 9995</div>
                            <div class="card-description">Fonds insuffisants</div>
                        </div>
                        <button class="copy-btn">Copier</button>
                    </div>
                </div>
            </div>

            <div class="test-cards">
                <h4><i class="fas fa-shield-alt text-warning me-2"></i>Authentification 3D Secure</h4>

                <div class="test-card secure" onclick="copyCard('4000 0025 0000 3155')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="card-number">4000 0025 0000 3155</div>
                            <div class="card-description">3D Secure requis (authentification réussie)</div>
                        </div>
                        <button class="copy-btn">Copier</button>
                    </div>
                </div>

                <div class="test-card secure" onclick="copyCard('4000 0027 6000 3184')">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="card-number">4000 0027 6000 3184</div>
                            <div class="card-description">3D Secure requis (authentification échouée)</div>
                        </div>
                        <button class="copy-btn">Copier</button>
                    </div>
                </div>
            </div>

            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Informations communes</h5>
                <ul class="mb-0">
                    <li><strong>Date d'expiration :</strong> N'importe quelle date future (ex: 12/25)</li>
                    <li><strong>CVC :</strong> N'importe quels 3 chiffres (ex: 123)</li>
                    <li><strong>Code postal :</strong> N'importe quel code postal (ex: 12345)</li>
                </ul>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('stripe.create-test-order') }}" class="btn btn-success btn-lg ms-2">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Test complet (55€)
                </a>

                <a href="{{ route('home') }}" class="btn btn-secondary btn-lg ms-2">
                    <i class="fas fa-home me-2"></i>
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyCard(cardNumber) {
            // Copier le numéro de carte dans le presse-papiers
            navigator.clipboard.writeText(cardNumber.replace(/\s/g, '')).then(function() {
                // Afficher une notification
                const notification = document.createElement('div');
                notification.className = 'alert alert-success position-fixed';
                notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 200px;';
                notification.innerHTML = `
            <i class="fas fa-check me-2"></i>
            Numéro de carte copié : ${cardNumber}
        `;

                document.body.appendChild(notification);

                // Supprimer la notification après 3 secondes
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }).catch(function(err) {
                console.error('Erreur lors de la copie :', err);
                alert('Numéro de carte : ' + cardNumber);
            });
        }

        // Ajouter des effets visuels
        document.querySelectorAll('.test-card').forEach(card => {
            card.addEventListener('click', function() {
                // Effet de clic
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
@endsection
