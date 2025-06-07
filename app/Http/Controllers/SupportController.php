<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index()
    {
        $tickets = SupportTicket::orderBy('id')->get();

        return view('support.index', compact('tickets'));
    }

    // SupportController.php
    public function showForm()
    {
        return view('support.form');
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Exemple : enregistrement ou envoi mail
        SupportTicket::create([
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        // Optionnel : notification/email
        // Mail::to('support@tonsite.com')->send(new SupportTicketSubmitted($ticket));

        return redirect()->route('support.form')->with('success', 'Votre demande a été envoyée.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
