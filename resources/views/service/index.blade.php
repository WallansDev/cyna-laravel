@extends('layouts.base')

@section('title', 'Services - ' . $_SOCIETYNAME)

@section('content')

    <div class="container">
        <h1>Les services</h1>
        @foreach ($services as $service)
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card mb-3 text-white service-category-card">
                        {{-- <img src="{{ asset('storage/services/' . $service->image_path) }}" class="card-img-top"
                            alt="{{ $service->name }}"> --}}
                        <div class="card-body text-white">
                            <h5 class="service-category-title">{{ $service->name }}</h5>
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ asset('storage/services/' . $service->image_path) }}" alt="{{ $service->name }}"
                                    class="img-fluid rounded" style="max-width: 100px; margin-right:8px;">
                                <div class="d-flex flex-column justify-content-center h-100">
                                    <p class="card-text mb-0" style="color:white;">{{ $service->description }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                @if ($service->availbility)
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-afficher mx-2">Afficher
                                        le service</a>
                                @else
                                    <a href="{{ route('services.show', $service->id) }}" class="btn btn-danger mx-2">Temporairement
                                        indisponible</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

<script>
    document.querySelectorAll('.toggle-top').forEach(el => {
        el.addEventListener('change', function() {
            const id = this.dataset.id;
            const checked = this.checked;
            const input = document.querySelector(`.top-position-input[data-id="${id}"]`);
            input.disabled = !checked;

            if (!checked) {
                input.value = 0;
            }

            updateTopPosition(id, input.value);
        });
    });

    document.querySelectorAll('.top-position-input').forEach(el => {
        el.addEventListener('change', function() {
            const id = this.dataset.id;
            updateTopPosition(id, this.value);
        });
    });

    function updateTopPosition(id, value) {
        fetch(`/services/${id}/update-top-position`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    top_position: value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Top position updated');
                }
            });
    }
</script>