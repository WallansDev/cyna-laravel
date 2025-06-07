<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TwoFactorCode;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.twoFactor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|integer',
        ], [
            'two_factor_code.required' => 'Veuillez saisir le code de vérification.',
            'two_factor_code.integer' => 'Le code de vérification doit être un nombre entier.',
        ]);

        $user = $request->user();

        if ($request->input('two_factor_code') == $user->two_factor_code) {

            if (date('Y-m-d H:i:s') <= $user->two_factor_expires_at) {
                $user->resetTwoFactorCode();

                return redirect()->route('dashboard');
            }
            return redirect()->back()
                ->withErrors(['two_factor_code' =>
                'Le code 2FA saisie à expiré.']);
        }

        return redirect()->back()
            ->withErrors(['two_factor_code' =>
            'Le code 2FA saisie incorrect.']);
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->generateTwoFactorCode(); // Génère un nouveau code
            $user->notify(new TwoFactorCode()); // Envoie la notification
        }

        return redirect()->back()->withMessage('Un nouveau code 2FA a été envoyé');
    }
}
