@extends('layouts.base')

@section('title', 'Mot de passe oublié - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">

        <div class="row">
            <div class="col-1"></div>
            <div class="mx-auto col-5">
                <h1>Reset Password</h1>
            </div>
        </div>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="row mt-3">
                <div class="col-1"></div>
                <div class="mx-auto col-5">
                        <label for="email">Email</label>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}"
                            required autofocus autocomplete="username">
                        @error('email')
                            <div class="error">{{ $message }}</div>
                        @enderror
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-1"></div>
                <div class="mx-auto col-5">
                    <label for="password">New Password</label>
                    <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-1"></div>
                <div class="mx-auto col-5">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required
                        autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-1"></div>
                <div class="mx-auto col-5">
                    <button type="submit" class="btn btn-success">Changer le mot de passe</button>
                </div>
            </div>
        </form>
    </div>

@endsection
