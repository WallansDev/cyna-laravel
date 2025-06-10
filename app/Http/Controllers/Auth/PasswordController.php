<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
        'current_password' => ['required', 'current_password'],
        'password' => ['required', Password::defaults(), 'confirmed'],
    ], [
        'current_password.required' => 'Veuillez saisir votre mot de passe actuel.',
        'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
        'password.required' => 'Veuillez entrer un nouveau mot de passe.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        'password.min' => 'Le mot de passe n\'est pas assez long.',
    ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Mot de passe mis à jour.');
    }
}
