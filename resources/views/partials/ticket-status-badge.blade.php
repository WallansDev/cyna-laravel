@php
    $statusLabels = [
        0 => ['label' => 'En cours', 'class' => 'bg-primary'],
        1 => ['label' => 'Fermé', 'class' => 'bg-danger'],
        2 => ['label' => 'Gelé', 'class' => 'bg-info'],
        3 => ['label' => 'Nouveau', 'class' => 'bg-success'],
    ];
@endphp

@if (isset($statusLabels[$ticket->status]))
    <span class="badge {{ $statusLabels[$ticket->status]['class'] }}">
        {{ $statusLabels[$ticket->status]['label'] }}
    </span>
@else
    <span class="badge bg-secondary">Inconnu</span>
@endif
