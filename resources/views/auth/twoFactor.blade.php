<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ "2FA $_SOCIETYNAME" }}</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">

    {{-- Font Awesome for icons --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style_base.css') }}">

    {{-- Additional CSS --}}
    <link rel="stylesheet" href="{{ asset('css/twofactor.css') }}">
</head>

<body>
    <div class="container mt-5 d-flex flex-column justify-content-center align-items-center">

        @if (session()->has('message'))
            <div class="alert alert-info w-100 text-center">
                {{ session()->get('message') }}
            </div>
        @endif

        <div class="text-center mb-2">
            <h1>Two Factor Verification</h1>
            <p>
                Vous avez reçu un e-mail contenant le code de connexion à deux facteurs.
                Si vous ne l'avez pas reçu, appuyez <a href="{{ route('verify.resend') }}">ici</a>.
            </p>
        </div>

        <form method="POST" action="{{ route('verify.store') }}" class="w-100" style="max-width: 400px;">
            @csrf

            <div class="input-group mt-3 mb-4">
                <span class="input-group-text p-3">
                    <i class="fa fa-lock"></i>
                </span>
                <input name="two_factor_code" type="text"
                    class="form-control @error('two_factor_code') is-invalid @enderror" required autofocus
                    placeholder="Two Factor Code">
                @error('two_factor_code')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex justify-content-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-secondary" type="submit">Annuler</button>
                </form>
                &ensp;
                <button style="background-color: #8535e4;" type="submit" class="btn btn-primary px-4">
                    Vérifier
                </button>
            </div>
        </form>

    </div>
</body>

</html>
