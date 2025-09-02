@extends('layouts.base')

@section('title', 'Services - ' . $_SOCIETYNAME)

@section('content')

    <div class="container">
        <h1 class="text-white mb-4" style="text-align: center;">Les services</h1>
        @foreach ($services as $service)
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card mb-3 text-white service-category-card">
                        <div class="card-body text-white">
                            <h5 class="service-category-title">{{ $service->name }}</h5>
                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-md-4 d-flex justify-content-center mb-2 mb-md-0">
                                    <img src="{{ asset('storage/services/' . $service->image_path) }}"
                                        alt="{{ $service->name }}" class="img-fluid rounded" style="max-width: 100px;">
                                </div>
                                <div class="col-12 col-md-8 d-flex flex-column justify-content-center h-100">
                                    <p class="card-text mb-0" style="color:white;">{{ $service->description }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center align-items-center">
                                @if ($service->availbility)
                                    <form action="{{ route('cart.add') }}" method="POST"
                                        class="add-to-cart-form justify-content-center align-items-center mb-0">
                                        <a href="{{ route('services.show', $service->id) }}"
                                            class="btn btn-afficher">Afficher
                                            le service</a>
                                        @csrf
                                        <input type="hidden" name="services_id" value="{{ $service->id }}">
                                    </form>
                                @else
                                    <a href="{{ route('services.show', $service->id) }}"
                                        class="btn btn-danger mx-2">Temporairement
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
