@extends('layouts.base')

@section('title', 'Services - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 85%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Services') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('services.create') }}" class="btn btn-gold btn-sm"
                                    data-placement="left">
                                    {{ __('Nouveau') }}
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
                                        <th style="text-align: center;">Position</th>
                                        <th style="text-align: center;">Titre</th>
                                        <th style="text-align: center;">Image</th>
                                        <th>Description</th>
                                        <th style="text-align: center;">Disponibilité</th>
                                        <th style="text-align: center;">Position en vedette</th>
                                        <th>Catégories</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr>
                                            <td class="align-middle text-center">
                                                @if ($service->position === $service_first->position)
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('services.down', $service->id) }}"><i class="bi bi-caret-down-fill"></i></a>
                                                @elseif ($service->position === $service_last->position)
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('services.up', $service->id) }}"><i class="bi bi-caret-up-fill"></i></a>
                                                @else
                                                <div class="d-flex flex-column align-items-center">
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('services.up', $service->id) }}"><i class="bi bi-caret-up-fill"></i></a>
                                                    <br class="mb-3">
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('services.down', $service->id) }}"><i class="bi bi-caret-down-fill"></i></a>
                                                </div>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">{{ $service->position }}</td>
                                            <td class="align-middle text-center">{{ $service->name }}</td>
                                            <td class="align-middle text-center"><img src="{{ asset('storage/services/' . $service->image_path) }}"
                                                    alt="{{ $service->image_path }}" width="100" class="category-image">
                                            </td>
                                            <td class="align-middle">{{ $service->description }}</td>
                                            @if ($service->availbility)
                                                <td class="align-middle text-center">
                                                    <span class="badge bg-success">Disponible</span>
                                                </td>
                                            @else
                                                <td class="align-middle text-center">
                                                    <span class="badge bg-danger">Indisponible</span>
                                                </td>
                                            @endif
                                            <td class="align-middle text-center">
                                                @if ($service->top_position != 0)
                                                    {{ $service->top_position }}
                                                @else
                                                    <span class="badge bg-danger">Pas en vedette</span>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                @forelse ($service->categories as $category)
                                                    <span class="badge bg-purple">{{ $category->name }}</span>
                                                @empty
                                                    <span class="badge bg-purple">Pas de catégorie</span>
                                                @endforelse
                                            </td>
                                            <td class="align-middle text-center">
                                                <form class="d-flex flex-column align-items-center gap-2" action="{{ route('services.destroy', $service->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm action-btn view-btn"
                                                       href="{{ route('services.show', $service->id) }}"><i class="bi bi-eye-fill"></i></a>
                                                    <a class="btn btn-sm action-btn edit-btn"
                                                        href="{{ route('services.edit', $service->id) }}"><i class="bi bi-pencil-fill"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm action-btn delete-btn"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
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
