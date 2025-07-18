@extends('layouts.base')

@section('title', 'Tickets - ' . $_SOCIETYNAME)

@section('head-content')
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
@endsection

@section('content')
    <div class="container">
        @if ($tickets->isEmpty())
            <h4>Vous n'avez aucun ticket.</h4>
        @else
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titre</th>
                        <th scope="col">Client</th>
                        <th scope="col">Etat</th>
                        <th scope="col">Date de création</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($tickets as $ticket)
                            <td scope="row"><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->id }}</a>
                            </td>
                            <td><a href="{{ route('tickets.show', $ticket->id) }}">{{ $ticket->subject }}</a></td>
                            <td>{{ $ticket->user->surname . ' ' . $ticket->user->name . ' (' . preg_replace("/^(\d{3})(\d{3})(\d{3})(\d{5})$/", "$1 $2 $3 $4", $ticket->user->siret) . ')' }}
                            </td>
                            <td>
                                @if ($ticket->status === 0)
                                    <span class="badge bg-primary">En cours</span>
                                @elseif ($ticket->status === 1)
                                    <span class="badge bg-danger">Fermé</span>
                                @elseif ($ticket->status === 2)
                                    <span class="badge bg-info">Gelé</span>
                                @elseif ($ticket->status === 3)
                                    <span class="badge bg-success">Nouveau</span>
                                @else
                                    Pas d'état trouvé
                                @endif
                            </td>

                            <td>{{ $ticket->created_at->format('d/m/Y à H:h') }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
@endsection
