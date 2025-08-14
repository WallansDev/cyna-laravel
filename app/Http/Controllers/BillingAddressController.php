<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingAddressController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->billingAddresses()->count() >= 5) {
            return back()->withErrors(['max' => 'Vous ne pouvez enregistrer que 5 adresses.']);
        }

        $validated = $request->validate([
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
        ]);

        $user->billingAddresses()->create($validated);

        return back()->with('success', 'Adresse enregistrée.');
    }
}
