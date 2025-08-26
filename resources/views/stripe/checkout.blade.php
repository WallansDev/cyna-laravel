@extends('layouts.base')

@section('title', 'Paiement - ' . config('app.name'))

@section('head-content')
    <style>
        .payment-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .payment-form {
            margin-top: 2rem;
        }

        .payment-element {
            margin-bottom: 2rem;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .submit-button {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-button:enabled {
            background: #007bff;
            color: white;
        }

        .submit-button:enabled:hover {
            background: #0056b3;
            transform: translateY(-2px);
        }

        .submit-button:disabled {
            background: #6c757d;
            color: #fff;
            cursor: not-allowed;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .payment-message {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
        }

        .payment-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .payment-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .amount-display {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="payment-container">
            <div class="text-center mb-4">
                <h1 class="h3 mb-3">Finaliser votre paiement</h1>
                <div class="amount-display">
                    {{ number_format($amount, 2) }} {{ $currency }}
                </div>
            </div>

            <form id="payment-form" class="payment-form">
                <div id="payment-element" class="payment-element">
                    <!-- Stripe Elements sera monté ici -->
                </div>

                <button id="submit" class="submit-button">
                    <div class="spinner hidden" id="spinner"></div>
                    <span id="button-text">Payer {{ number_format($amount, 2) }} {{ $currency }}</span>
                </button>

                <div id="payment-message" class="payment-message hidden"></div>
            </form>

            <div class="mt-4 text-center">
                <small class="text-muted">
                    <i class="fas fa-lock me-1"></i>
                    Vos informations de paiement sont sécurisées par Stripe
                </small>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser Stripe
            const stripe = Stripe('{{ $stripeKey }}');
            const elements = stripe.elements({
                clientSecret: '{{ $clientSecret }}'
            });

            // Créer l'élément de paiement
            const paymentElement = elements.create('payment', {
                layout: 'tabs',
                defaultValues: {
                    billingDetails: {
                        name: '{{ auth()->user()->name ?? '' }}',
                        email: '{{ auth()->user()->email ?? '' }}'
                    }
                }
            });

            // Monter l'élément
            paymentElement.mount('#payment-element');

            // Gérer la soumission du formulaire
            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit');
            const spinner = document.getElementById('spinner');
            const buttonText = document.getElementById('button-text');
            const messageContainer = document.getElementById('payment-message');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                setLoading(true);

                const {
                    error
                } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: '{{ route('stripe.success') }}',
                    },
                });

                if (error.type === 'card_error' || error.type === 'validation_error') {
                    showMessage(error.message, 'error');
                } else {
                    showMessage('Une erreur inattendue s\'est produite.', 'error');
                }

                setLoading(false);
            });

            function setLoading(isLoading) {
                if (isLoading) {
                    submitButton.disabled = true;
                    spinner.classList.remove('hidden');
                    buttonText.classList.add('hidden');
                } else {
                    submitButton.disabled = false;
                    spinner.classList.add('hidden');
                    buttonText.classList.remove('hidden');
                }
            }

            function showMessage(messageText, type = 'error') {
                messageContainer.textContent = messageText;
                messageContainer.className = `payment-message ${type}`;
                messageContainer.classList.remove('hidden');

                setTimeout(() => {
                    messageContainer.classList.add('hidden');
                    messageContainer.textContent = '';
                }, 5000);
            }

            // Afficher les messages d'erreur de session si présents
            @if (session('error'))
                showMessage('{{ session('error') }}', 'error');
            @endif

            @if (session('success'))
                showMessage('{{ session('success') }}', 'success');
            @endif
        });
    </script>
@endsection
