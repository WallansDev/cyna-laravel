<?php

namespace App\Http\Controllers;

use App\Mail\CustomMail;
use App\Models\Order;
use App\Models\User;
use App\Models\StripePayment;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Mail;

class CustomMailController extends Controller
{
    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    /**
     * Page de test pour envoyer des emails
     */
    public function index()
    {
        $users = User::all();
        
        return view('admin.custom-email', compact('users'));
    }

   
    /**
     * Envoyer un email personnalisé de test
     */
    public function sendCustomEmail(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $result = Mail::to($user->email)->send(new CustomMail($request->subject, $request->message, $user));

        if ($result) {
            return back()->with('success', 'Email personnalisé envoyé avec succès à ' . $user->email);
        } else {
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email');
        }
    }
}
