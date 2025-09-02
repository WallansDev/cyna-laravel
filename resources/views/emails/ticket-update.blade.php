<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message sur le ticket #{{ $ticket->id }} - Cyna SaaS</title>
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

        a {
            text-decoration: none;
        }

        .content {
            padding: 20px;
            background-color: #f8f9fa;
        }

        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #3182ce;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
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
        <h1>Nouveau message sur le ticket #{{ $ticket->id }}</h1>
    </div>

    <div class="content">
        <p>Un utilisateur à poster un nouveau message sur le ticket {{ $ticket->id }}</p>


        <div class="order-details">
            <h4>Sujet : {{ $ticket->subject }}</h4>
            <p><b>Date de création : </b>{{ $ticket->created_at->format('d/m/Y H:i') }}</p>
            <h4><b>Message :</b></h4>
            <p>{!! nl2br(e($ticketMessage->content)) !!}</p>
        </div>

        <a href="{{ env('APP_URL') . "/users/tickets/$ticket->id" }}" style="color:white;" class="btn">Accéder au
            ticket</a>

    </div>

    <div class="footer">
        <p>Merci de votre confiance,<br>L'équipe Cyna</p>
        <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
    </div>
</body>

</html>
