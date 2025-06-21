@extends('layouts.base')

@section('title', 'Services - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Services') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('services.create') }}" class="btn btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Create New') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th></th>
                                        <th>Position</th>
                                        <th>Title</th>
                                        <th>Image Path</th>
                                        <th>Description</th>
                                        <th>Availbility</th>
                                        <th>Position Top produit</th>
                                        <th>Catégories</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr>
                                            <td>
                                                @if ($service->position === $service_first->position)
                                                    <br>
                                                    <a href="{{ route('services.down', $service->id) }}">🔽</a>
                                                @elseif ($service->position === $service_last->position)
                                                    <a href="{{ route('services.up', $service->id) }}">🔼</a>
                                                @else
                                                    <a href="{{ route('services.up', $service->id) }}">🔼</a>
                                                    <a href="{{ route('services.down', $service->id) }}">🔽</a>
                                                @endif
                                            </td>
                                            <td>{{ $service->position }}</td>
                                            <td>{{ $service->name }}</td>
                                            <td><img src="{{ asset('storage/services/' . $service->image_path) }}"
                                                    alt="{{ $service->image_path }}" width="20%">
                                            </td>
                                            <td>{{ $service->description }}</td>
                                            @if ($service->availbility === 1)
                                                <td>✅ Disponible</td>
                                            @else
                                                <td>❌ Indisponible</td>
                                            @endif
                                            <td>
                                                @if ($service->top_position != 0)
                                                    {{ $service->top_position }}
                                                @else
                                                    ❌ Non
                                                @endif
                                            </td>
                                            <td>
                                                @forelse ($service->categories as $category)
                                                    <span class="badge bg-secondary">{{ $category->name }}</span>
                                                @empty
                                                    Pas de catégorie
                                                @endforelse
                                            </td>
                                            <td>
                                                <form action="{{ route('services.destroy', $service->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('services.edit', $service->id) }}">✏️</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">🗑️</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $services->links() }}
                    </div>
                </div>
            </div>
        </div>
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
