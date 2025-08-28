<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\BillingAddressRequest;
use App\Models\User;
use App\Models\BillingAddress;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $user = new User();

        return view('users.create', compact('user'));
    }

    public function store(UserRequest $request)
    {
        // dd($request->all());
        $user = $request->validated();
        $user['is_admin'] = $request->has('is_admin') ? 1 : 0;

        // Créer l'utilisateur - created_at sera automatiquement défini
        $newUser = User::create($user);
        
        // Vous pouvez accéder aux timestamps comme ceci :
        // $newUser->created_at - Date de création
        // $newUser->updated_at - Date de modification (égale à created_at lors de la création)

        // User::create($request->validated());

        return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès');
    }

    // Show specific User
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);

        $user->is_admin = $request->has('is_admin') ? 1 : 0;
        $user->save();

        // Mettre à jour l'utilisateur - updated_at sera automatiquement mis à jour
        $user->update($request->validated());
        
        // Vous pouvez accéder aux timestamps comme ceci :
        // $user->created_at - Date de création (inchangée)
        // $user->updated_at - Date de dernière modification (mise à jour automatiquement)

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès');
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Créer une nouvelle adresse de facturation pour l'utilisateur connecté
     *
     * @param BillingAddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBillingAddress(BillingAddressRequest $request)
    {
        $userId = Auth::id();

        // Vérifier la limite de 5 adresses par utilisateur
        $addressCount = BillingAddress::where('user_id', $userId)->count();
        
        if ($addressCount >= 5) {
            return back()->with('error', 'Limite atteinte : vous ne pouvez pas avoir plus de 5 adresses de facturation.');
        }

        try {
            // Créer l'adresse de facturation - created_at et updated_at seront automatiquement définis
            $newAddress = BillingAddress::create([
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'phone' => $request->phone,
                'user_id' => $userId,
            ]);

            // Vous pouvez accéder aux timestamps comme ceci :
            // $newAddress->created_at - Date de création
            // $newAddress->updated_at - Date de modification (égale à created_at lors de la création)

            return back()->with('success', 'Adresse de facturation ajoutée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout de l\'adresse de facturation.');
        }
    }

    /**
     * Afficher les adresses de facturation de l'utilisateur connecté
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function billingAddresses()
    {
        // Récupérer les adresses avec les timestamps
        $addresses = BillingAddress::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc') // Plus récentes en premier
            ->get();
        
        return view('profile.billing-addresses', compact('addresses'));
    }

    /**
     * Modifier une adresse de facturation
     *
     * @param BillingAddressRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBillingAddress(BillingAddressRequest $request, $id)
    {
        $address = BillingAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) {
            return back()->with('error', 'Adresse non trouvée.');
        }

        try {
            // Mettre à jour l'adresse - updated_at sera automatiquement mis à jour
            $address->update([
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'phone' => $request->phone,
            ]);

            // Vous pouvez accéder aux timestamps comme ceci :
            // $address->created_at - Date de création (inchangée)
            // $address->updated_at - Date de dernière modification (mise à jour automatiquement)

            return back()->with('success', 'Adresse de facturation modifiée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification de l\'adresse de facturation.');
        }
    }

    /**
     * Supprimer une adresse de facturation
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyBillingAddress($id)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Vous devez être connecté.');
        }

        $address = BillingAddress::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$address) {
            return back()->with('error', 'Adresse non trouvée.');
        }

        $address->delete();

        return back()->with('success', 'Adresse de facturation supprimée avec succès.');
    }
}
