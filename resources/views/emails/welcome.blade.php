<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue chez Cyna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #1a202c;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background-color: #f8f9fa;
        }

        .welcome-bonus {
            background-color: #e6fffa;
            border: 2px solid #38b2ac;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            text-align: center;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
        }

        a {
            text-decoration: none;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3182ce;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Bienvenue chez Cyna !</h1>
    </div>

    <div class="content">
        <p>Bonjour {{ $user->surname }},</p>

        @if ($customMessage)
            <p>{{ $customMessage }}</p>
        @else
            <p>Nous sommes ravis de vous compter parmi nos nouveaux membres ! Toute l’équipe de Cyna vous souhaite la
                bienvenue.</p>
            <p>À partir d’aujourd’hui, vous aurez accès à l'entièreté de nos services et contenus disponibles. <br>Notre
                objectif est de vous offrir la meilleure expérience possible et de vous accompagner à chaque étape.
            </p>
        @endif

        <p>Voici ce que vous pouvez faire dès maintenant :</p>
        <ul>
            <li><a href="{{ env('APP_URL') . '/users/profil' }}" style="color:black;">Accéder à votre espace personnel</a>
            </li>
            <li><a href="{{ env('APP_URL') . '/users/tickets' }}" style="color:black;">Contactez-nous en cas de
                    besoin</a></li>
        </ul>

        <a href="{{ env('APP_URL') . '/services' }}" style="color:white;" class="btn">Découvrir nos services</a>
    </div>

    <div class="footer">
        <p>Merci de votre confiance,<br>L'équipe Cyna</p>
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>

</html>
