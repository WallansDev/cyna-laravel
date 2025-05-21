<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        User::create($user);

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


        $user->update($request->validated());

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé');
    }
}
