@extends('layouts.base')

@section('title', 'Modification du mot de passe - ' . $_SOCIETYNAME)

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="card purple-header text-white shadow-lg border-0">
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success text-center">{{ session('success') }}</div>
                        @elseif ($errors->updatePassword->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->updatePassword->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="current_password" class="mb-1">Mot de passe actuel</label>
                                <input type="password" name="current_password" id="current_password"
                                    class="form-control text-white"
                                    style="background-color: var(--primary-color); border: none;" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="mb-1">Nouveau mot de passe</label>
                                <input type="password" name="password" id="password"
                                    class="form-control text-white"
                                    style="background-color: var(--primary-color); border: none;" required>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="mb-1">Confirmation</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control text-white"
                                    style="background-color: var(--primary-color); border: none;" required>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary px-5">Changer le mot de passe</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
