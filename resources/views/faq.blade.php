@extends('layouts.base')

@section('title', 'FAQ')

@section('content')
<div class="container" style="margin-top: 5em;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card purple-theme">
                <div class="card-header purple-header">
                    <span id="card_title">Foire Aux Questions (FAQ)</span>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item bg-transparent border-0">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button bg-dark text-white border-bottom border-secondary faq-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color: #212529 !important; color: #fff;">
                                    Comment puis-je passer une commande sur Cyna ?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #2C3034 !important; color: #fff;">
                                    Pour passer une commande, il suffit de parcourir notre catalogue, d’ajouter les produits souhaités à votre panier, puis de suivre le processus de commande en ligne.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-transparent border-0">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed bg-dark text-white border-bottom border-secondary faq-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="background-color: #212529 !important; color: #fff;">
                                    Quels sont les moyens de paiement acceptés ?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #2C3034 !important; color: #fff;">
                                    Nous acceptons les paiements par carte bancaire, PayPal et virement bancaire.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-transparent border-0">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed bg-dark text-white border-bottom border-secondary faq-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="background-color: #212529 !important; color: #fff;">
                                    Comment suivre ma commande ?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #2C3034 !important; color: #fff;">
                                    Après validation de votre commande, vous recevrez un email avec un lien de suivi. Vous pouvez également consulter l’état de votre commande dans votre espace client.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-transparent border-0">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed bg-dark text-white border-bottom border-secondary faq-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="background-color: #212529 !important; color: #fff;">
                                    Puis-je retourner un produit ?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #2C3034 !important; color: #fff;">
                                    Oui, vous disposez d’un délai de 14 jours après réception pour retourner un produit non utilisé. Consultez notre page Retours et remboursements pour plus de détails.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-transparent border-0">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed bg-dark text-white border-bottom border-secondary faq-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" style="background-color: #212529 !important; color: #fff;">
                                    Comment contacter le service client ?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                <div class="accordion-body" style="background-color: #2C3034 !important; color: #fff;">
                                    Vous pouvez nous contacter via le formulaire de contact ou par email à support@cyna.fr.
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        /* Affiche toujours la flèche et la met en blanc */
                        .faq-toggle::after {
                            filter: brightness(0) invert(1);
                            opacity: 1;
                        }
                        .faq-toggle:not(.collapsed)::after {
                            filter: brightness(0) invert(1);
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
