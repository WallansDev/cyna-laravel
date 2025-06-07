<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::all()->count();
        $admin_users = User::where('is_admin', true)->count();

        return view('dashboard', compact('users', 'admin_users'));
    }
}
