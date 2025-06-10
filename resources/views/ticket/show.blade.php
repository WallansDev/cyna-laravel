@extends('layouts.base')

@section('title', 'Ticket #' . $ticket->id . ' - ' . $_SOCIETYNAME)

@section('head-content')
    <link rel="stylesheet" href="{{ asset('css/ticket.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="card col-12">

                <div class="card-header d-flex justify-content-between align-items-center">
                    Ticket #{{ $ticket->id }} - {{ $ticket->subject }}

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
                </div>
                <div class="card-body" id="messages">

                    @foreach ($ticket->messages as $message)
                        <div
                            class="d-flex {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                            <div class="p-2 rounded {{ $message->user_id === auth()->id() ? 'bg-custom-blue text-white' : 'bg-light' }}"
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

                <form action="{{ route('messages.store', $ticket) }}" method="POST">
                    @csrf
                    <div class="input-group mt-3">
                        <input type="text" name="content" class="form-control" placeholder="Écrire un message..."
                            required>
                        <button class="btn btn-primary" type="submit">Envoyer</button>
                    </div>
                </form>
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
            fetch(`/tickets/${ticketId}/update-status`, {
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
                .then(response => response.json())
                .then(data => {
                    // Cibler précisément le bon bouton
                    const dropdownButton = document.getElementById('status-dropdown-btn');
                    if (dropdownButton) {
                        dropdownButton.innerHTML = data.badge;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la mise à jour du statut', error);
                });
        }
    </script>
@endsection
