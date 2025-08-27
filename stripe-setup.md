# Configuration Stripe pour Laravel

## 1. Variables d'environnement

Ajoutez ces variables dans votre fichier `.env` :

```env
# Stripe Configuration
STRIPE_PUBLISHABLE_KEY=pk_test_votre_cle_publique
STRIPE_SECRET_KEY=sk_test_votre_cle_secrete
STRIPE_WEBHOOK_SECRET=whsec_votre_cle_webhook
STRIPE_CURRENCY=eur
STRIPE_DEFAULT_AMOUNT=2000
```

## 2. Obtenir vos clés Stripe

1. Créez un compte sur [stripe.com](https://stripe.com)
2. Allez dans le Dashboard Stripe
3. Dans "Developers" > "API keys"
4. Copiez vos clés de test (commençant par `pk_test_` et `sk_test_`)

## 3. Configuration des webhooks

1. Dans le Dashboard Stripe, allez dans "Developers" > "Webhooks"
2. Cliquez sur "Add endpoint"
3. URL : `https://votre-domaine.com/webhook/stripe`
4. Événements à écouter :
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `payment_intent.canceled`
5. Copiez la clé de signature webhook (commençant par `whsec_`)

## 4. Cartes de test Stripe

Utilisez ces cartes pour tester :

- **Succès** : `4242 4242 4242 4242`
- **Échec** : `4000 0000 0000 0002`
- **3D Secure** : `4000 0025 0000 3155`
- **Carte expirée** : `4000 0000 0000 0069`

Date d'expiration : n'importe quelle date future
CVC : n'importe quels 3 chiffres

## 5. Intégration avec votre panier

Pour intégrer avec votre panier existant, ajoutez un bouton "Payer" dans votre vue panier :

```html
<a href="{{ route('order.checkout') }}" class="btn btn-primary">
    <i class="fas fa-credit-card me-2"></i>Finaliser la commande
</a>
```

## 6. Test de l'intégration

1. Ajoutez des produits à votre panier
2. Allez sur `/order/checkout`
3. Remplissez les informations de facturation
4. Cliquez sur "Procéder au paiement"
5. Utilisez une carte de test Stripe
6. Vérifiez la redirection vers la page de succès

## 7. Personnalisation

Vous pouvez personnaliser :
- Les montants dans `StripeController.php`
- Les vues dans `resources/views/stripe/`
- Les styles dans `public/css/stripe.css`
- La configuration dans `config/stripe.php`

## 8. Production

Pour passer en production :
1. Remplacez les clés de test par les clés de production
2. Configurez les webhooks de production
3. Testez avec de vraies cartes
4. Activez la vérification SSL

## 9. Sécurité

- Ne committez jamais vos clés secrètes
- Utilisez HTTPS en production
- Validez toujours les webhooks
- Gérez les erreurs de paiement
- Sauvegardez les transactions en base de données 