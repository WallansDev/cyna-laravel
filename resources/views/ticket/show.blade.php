@extends('layouts.base')

@section('title', 'Ticket #' . $ticket->id . ' - ' . $_SOCIETYNAME)

@section('head-content')
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
@endsection

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 60%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                Ticket #{{ $ticket->id }} - {{ $ticket->subject }}
                            </span>

                            @if (auth()->check() && auth()->user()->isAdmin())
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle" type="button" id="status-dropdown-btn"
                                        data-bs-toggle="dropdown">
                                        @include('partials.ticket-status-badge', ['ticket' => $ticket])
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#"
                                                onclick="updateTicketStatus({{ $ticket->id }}, 0)">En cours</a></li>
                                        <li><a class="dropdown-item" href="#"
                                                onclick="updateTicketStatus({{ $ticket->id }}, 1)">Fermé</a></li>
                                        <li><a class="dropdown-item" href="#"
                                                onclick="updateTicketStatus({{ $ticket->id }}, 2)">Gelé</a></li>
                                        <li><a class="dropdown-item" href="#"
                                                onclick="updateTicketStatus({{ $ticket->id }}, 3)">Nouveau</a></li>
                                    </ul>
                                </div>
                            @else
                                <div>
                                    @include('partials.ticket-status-badge', ['ticket' => $ticket])
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body m-3" id="messages">

                        @foreach ($ticket->messages as $message)
                            <div
                                class="d-flex {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                                <div class="p-2 rounded {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-primary-subtle' }}"
                                    style="max-width: 70%;">
                                    <small><strong>{{ $message->user->surname . ' ' . $message->user->name }}
                                        </strong>
                                        @if ($message->user->is_admin)
                                            {{ '- Administrateur' }}
                                        @endif
                                    </small><br>
                                    {{ $message->content }}

                                    <div class="text-end mt-2">
                                        <small class="text-muted">{{ $message->created_at->format('d M Y à H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($ticket->status !== 1 && $ticket->status !== 2)
                        <form class="m-3 mt-1" action="{{ route('messages.store', $ticket) }}" method="POST" id="message-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="content" class="form-control" placeholder="Écrire un message..."
                                    required>
                                <button class="btn btn-primary" type="submit">Envoyer</button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning m-3 mt-1" role="alert">
                            <i class="fas fa-lock me-2"></i>
                            Ce ticket est {{ $ticket->status === 1 ? 'fermé' : 'gelé' }}. Aucun nouveau message ne peut
                            être ajouté.
                        </div>
                    @endif
                    <br>
                </div>
            </div>
        </div>
        <br><br>

        <script>
            window.addEventListener('load', function() {
                const messagesContainer = document.getElementById('messages');
                if (messagesContainer) {
                    messagesContainer.scrollTo({
                        top: messagesContainer.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            });

            function updateTicketStatus(ticketId, status) {
                fetch(`/users/tickets/${ticketId}/update-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            status
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 403) {
                                alert('Accès refusé. Seuls les administrateurs peuvent modifier le statut des tickets.');
                            } else {
                                throw new Error('Erreur lors de la mise à jour du statut');
                            }
                            return;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data) {
                            // Cibler précisément le bon bouton
                            const dropdownButton = document.getElementById('status-dropdown-btn');
                            if (dropdownButton) {
                                dropdownButton.innerHTML = data.badge;
                            }

                            // Mettre à jour l'interface selon le nouveau statut
                            updateMessageFormVisibility(status);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de la mise à jour du statut', error);
                        alert('Une erreur est survenue lors de la mise à jour du statut.');
                    });
            }

            function updateMessageFormVisibility(status) {
                const messageForm = document.getElementById('message-form');
                const messageFormContainer = messageForm ? messageForm.parentElement : null;

                if (status === 1 || status === 2) { // Fermé ou Gelé
                    // Masquer le formulaire et afficher le message d'alerte
                    if (messageForm) {
                        messageForm.style.display = 'none';
                    }

                    // Créer ou mettre à jour le message d'alerte
                    let alertDiv = document.querySelector('.alert-warning');
                    if (!alertDiv) {
                        alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-warning mt-3';
                        alertDiv.setAttribute('role', 'alert');
                        messageFormContainer.appendChild(alertDiv);
                    }

                    const statusText = status === 1 ? 'fermé' : 'gelé';
                    alertDiv.innerHTML =
                        `<i class="fas fa-lock me-2"></i>Ce ticket est ${statusText}. Aucun nouveau message ne peut être ajouté.`;
                } else {
                    // Afficher le formulaire et masquer le message d'alerte
                    if (messageForm) {
                        messageForm.style.display = 'block';
                    }

                    const alertDiv = document.querySelector('.alert-warning');
                    if (alertDiv) {
                        alertDiv.remove();
                    }
                }
            }
        </script>
    @endsection
