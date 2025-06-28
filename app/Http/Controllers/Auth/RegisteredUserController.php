<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:50'],
                'surname' => ['required', 'string', 'max:30'],
                'phone' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:319', 'unique:' . User::class],
                'siret' => ['required', 'string', 'max:14'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ],
            [
                'name.required' => 'Le nom est obligatoire.',
                'name.max' => 'Le nom ne doit pas dépaser 50 caractères.',

                'surname.required' => 'Le prénom est obligatoire.',
                'surname.max' => 'Le prénom ne doit pas dépaser 30 caractères.',

                'phone.required' => 'Le numéro de téléphone est obligatoire.',

                'email.required' => 'L’adresse e-mail est obligatoire.',
                'email.email' => 'Le format de l’adresse e-mail est invalide.',
                'email.unique' => 'Cet e-mail est déjà utilisé.',

                'siret.required' => 'Le numéro SIRET est obligatoire.',
                'siret.max' => 'Le numéro SIRET ne doit pas dépasser 14 chiffres.',

                'password.required' => 'Le mot de passe est obligatoire.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            ],
        );

        if (!$this->verifySiretExists($request->siret)) {
            return back()
                ->withErrors(['siret' => 'Le numéro SIRET est invalide ou introuvable.'])
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'email' => $request->email,
            'siret' => $request->siret,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('profile.index', absolute: false));
    }

    private function verifySiretExists(string $siret): bool
    {
        $response = Http::withHeaders([
            'X-Client-Secret' => 'UjPrMG9yhDJPPXQy8j2TKVlnURIoVHhO',
        ])->get("https://data.siren-api.fr/v3/etablissements/{$siret}");

        return $response->successful() && isset($response['etablissement']);
    }
}
