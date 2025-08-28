<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Stripe\StripeClient;

class StripeCouponController extends Controller
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.secret_key'));
    }

    public function index()
    {
        // Récupérer une liste de coupons et de promotion codes
        $coupons = $this->stripe->coupons->all(['limit' => 100]);
        // Construire une map des codes actifs par coupon pour un rendu fiable
        $couponIdToActiveCodes = [];
        foreach (($coupons->data ?? []) as $coupon) {
            try {
                $codes = $this->stripe->promotionCodes->all([
                    'coupon' => $coupon->id,
                    'active' => true,
                    'limit' => 100,
                ]);
                $couponIdToActiveCodes[$coupon->id] = collect($codes->data ?? [])
                    ->pluck('code')
                    ->values()
                    ->all();
            } catch (\Exception $e) {
                $couponIdToActiveCodes[$coupon->id] = [];
            }
        }

        return view('admin.coupons.index', [
            'coupons' => $coupons->data ?? [],
            'couponIdToActiveCodes' => $couponIdToActiveCodes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:3|max:32',
            'type' => 'required|in:percent,amount',
            'value' => 'required|numeric|min:0.01',
            'currency' => 'required_if:type,amount|nullable|string|size:3',
            'max_redemptions' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $code = strtoupper(trim($request->input('code')));
        $type = $request->input('type');
        $value = (float) $request->input('value');
        $currency = $request->input('currency');
        $maxRedemptions = $request->input('max_redemptions');
        $expiresAt = $request->input('expires_at') ? strtotime($request->input('expires_at')) : null;

        try {
            // Vérifier si le code existe déjà
            $existing = $this->stripe->promotionCodes->all([
                'code' => $code,
                'limit' => 1,
            ]);
            if (!empty($existing->data)) {
                return back()->with('error', 'Ce code existe déjà sur Stripe.');
            }

            // Créer le coupon Stripe
            if ($type === 'percent') {
                if ($value > 100) {
                    return back()->with('error', 'La remise en pourcentage ne peut pas dépasser 100%.');
                }
                $coupon = $this->stripe->coupons->create([
                    'percent_off' => $value,
                    'duration' => 'once',
                ]);
            } else {
                if (!$currency) {
                    return back()->with('error', 'La devise est requise pour une remise fixe.');
                }
                $coupon = $this->stripe->coupons->create([
                    'amount_off' => (int) round($value * 100),
                    'currency' => strtolower($currency),
                    'duration' => 'once',
                ]);
            }

            // Créer le promotion code lié
            $params = [
                'coupon' => $coupon->id,
                'code' => $code,
                'active' => true,
            ];
            if ($maxRedemptions) {
                $params['max_redemptions'] = (int) $maxRedemptions;
            }
            if ($expiresAt) {
                $params['expires_at'] = $expiresAt;
            }

            $this->stripe->promotionCodes->create($params);

            return redirect()->route('admin.coupons.index')->with('success', 'Coupon créé sur Stripe.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur Stripe: ' . $e->getMessage());
        }
    }

    public function destroy($couponId)
    {
        try {
            // Désactiver tous les promotion codes liés
            $promoCodes = $this->stripe->promotionCodes->all([
                'coupon' => $couponId,
                'limit' => 100,
            ]);
            foreach ($promoCodes->data as $promo) {
                if ($promo->active) {
                    $this->stripe->promotionCodes->update($promo->id, ['active' => false]);
                }
            }

            // Tenter de supprimer le coupon (peut échouer s'il a été utilisé)
            try {
                $this->stripe->coupons->delete($couponId);
            } catch (\Exception $e) {
                // Ignorer si suppression impossible; les codes sont désactivés
            }

            return back()->with('success', 'Coupon supprimé/désactivé.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur Stripe: ' . $e->getMessage());
        }
    }
}


