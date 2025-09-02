@extends('layouts.base')

@section('title', 'Tickets - ' . $_SOCIETYNAME)

@section('head-content')
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
@endsection

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">

        @if ($tickets->isEmpty())
            <div class="row">
                <div class="col-sm-12 d-flex justify-content-center">
                    <div class="card purple-theme" style="width: 50%" data-bs-theme="dark">
                        <div class="purple-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span id="card_title">Liste des tickets</span>
                                <a href="{{ route('tickets.create') }}" class="btn btn-gold btn-sm">
                                    {{ __('Créer un ticket') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <p>Vous n'avez aucun ticket</p>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-sm-12 d-flex justify-content-center">
                    <div class="card purple-theme" style="width: 50%" data-bs-theme="dark">
                        <div class="purple-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span id="card_title">Liste des tickets</span>
                                <a href="{{ route('tickets.create') }}" class="btn btn-gold btn-sm">
                                    {{ __('Créer un ticket') }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="align-middle text-center" scope="col">N°</th>
                                        <th scope="col">Titre</th>
                                        <th scope="col">Client</th>
                                        <th class="align-middle text-center" scope="col">Etat</th>
                                        <th class="align-middle text-center" scope="col">Date de création</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tickets as $ticket)
                                        <tr>
                                            <td class="align-middle text-center" scope="row">
                                                {{ $ticket->id }}
                                            </td>
                                            <td>
                                                {{ $ticket->subject }}
                                            </td>
                                            <td>{{ $ticket->user->surname . ' ' . $ticket->user->name . ' (' . preg_replace("/^(\d{3})(\d{3})(\d{3})(\d{5})$/", "$1 $2 $3 $4", $ticket->user->siret) . ')' }}
                                            </td>
                                            <td class="align-middle text-center">
                                                @if ($ticket->status == 0)
                                                    <span class="badge bg-primary">En cours</span>
                                                @elseif ($ticket->status == 1)
                                                    <span class="badge bg-danger">Fermé</span>
                                                @elseif ($ticket->status == 2)
                                                    <span class="badge bg-info">Gelé</span>
                                                @elseif ($ticket->status == 3)
                                                    <span class="badge bg-success">Nouveau</span>
                                                @else
                                                    Pas d'état trouvé
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                {{ $ticket->created_at->format('d/m/Y à H:h') }}
                                            </td>
                                            <td class="align-middle text-center">
                                                <a class="btn btn-sm action-btn view-btn"
                                                    href="{{ route('tickets.show', $ticket->id) }}">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
