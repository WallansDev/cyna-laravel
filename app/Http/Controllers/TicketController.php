<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $tickets = Ticket::orderBy('id')->get();

        if (auth()->user()->is_admin) {
            // Admin : voir tous les tickets
            $tickets = Ticket::orderBy('id')->get();
        } else {
            // Utilisateur : voir uniquement ses propres tickets
            $tickets = Ticket::where('user_id', auth()->id())
                ->orderBy('id')
                ->get();
        }
        return view('ticket.index', compact('tickets'));
    }

    // SupportController.php
    public function showForm()
    {
        return view('ticket.form');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        // Sécurité
        if (auth()->id() !== $ticket->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|integer|in:0,1,2,3',
        ]);

        $ticket->status = $request->status;
        $ticket->save();

        // On renvoie le HTML du badge à mettre à jour
        $badgeHtml = view('partials.ticket-status-badge', ['ticket' => $ticket])->render();

        return response()->json(['badge' => $badgeHtml]);
    }

    public function submitForm(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
        ]);

        // Exemple : enregistrement ou envoi mail
        Ticket::create([
            'user_id' => Auth::user()->id,
            'subject' => $request->subject,
        ]);

        // Optionnel : notification/email
        // Mail::to('support@tonsite.com')->send(new SupportTicketSubmitted($ticket));

        return redirect()->route('tickets.form')->with('success', 'Votre demande a été envoyée.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::findOrFail($id);

        if (auth()->id() !== $ticket->user_id && !auth()->user()->is_admin) {
            abort(403);
        }

        return view('ticket.show', compact('ticket'));
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
