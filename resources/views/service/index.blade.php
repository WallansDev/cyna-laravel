@extends('layouts.base')

@section('title', 'Services - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 5em;">
        <div class="row">
            <div class="col-sm-12">
                <div class="card purple-theme">
                    <div class="card-header purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Services') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('services.create') }}" class="btn purple-btn-primary btn-sm float-right"
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

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-dark">
                                <thead>
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
                                                    <a href="{{ route('service.down', $service->id) }}"><i class="fa-solid fa-square-caret-down"></i></a>
                                                @elseif ($service->position === $service_last->position)
                                                    <a href="{{ route('service.up', $service->id) }}"><i class="fa-solid fa-square-caret-up"></i></a>
                                                @else
                                                    <a href="{{ route('service.up', $service->id) }}"><i class="fa-solid fa-square-caret-up"></i></a>
                                                    <a href="{{ route('service.down', $service->id) }}"><i class="fa-solid fa-square-caret-down"></i></a>
                                                @endif
                                            </td>
                                            <td>{{ $service->position }}</td>
                                            <td>{{ $service->name }}</td>
                                            <td>
                                                <img src="{{ asset('storage/services/' . $service->image_path) }}"
                                                    alt="{{ $service->image_path }}" width="20%" class="category-image">
                                            </td>
                                            <td>{{ $service->description }}</td>
                                            <td>
                                                @if ($service->availbility === 1)
                                                    <span class="badge bg-purple"><i class="fa-regular fa-square-check" style="color: rgb(7, 213, 7) "></i> Disponible</span>
                                                @else
                                                    <span class="badge" style="background-color: #dc3545;"><i class="fa-solid fa-xmark"></i> Indisponible</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($service->top_position != 0)
                                                    <span class="badge bg-purple">{{ $service->top_position }}</span>
                                                @else
                                                    <span class="text-muted"><i class="fa-solid fa-xmark"></i> Non</span>
                                                @endif
                                            </td>
                                            <td>
                                                @forelse ($service->categories as $category)
                                                    <span class="badge bg-purple">{{ $category->name }}</span>
                                                @empty
                                                    <span class="text-muted">Pas de catégorie</span>
                                                @endforelse
                                            </td>
                                            <td>
                                                <form action="{{ route('services.destroy', $service->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm edit-btn action-btn"
                                                        href="{{ route('services.edit', $service->id) }}"><i class="fa-solid fa-pencil"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn delete-btn btn-sm action-btn"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa-solid fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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