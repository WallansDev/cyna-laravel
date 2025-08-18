@extends('layouts.base')

@section('title', 'Services en vedette - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 50%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Services en vedette') }}
                            </span>
                            
                            <div class="float-right">
                                <a href="{{ route('services.viewAdmin') }}" class="btn btn-gold btn-sm"
                                    data-placement="left">
                                    {{ __('Modifier les services') }}
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
                            <table class="table table-dark table-striped">
                                <thead class="thead">
                                    <tr>
                                        <th></th>
                                        <th class="align-middle text-center">Position</th>
                                        <th class="align-middle text-center">Service</th>
                                        <th class="align-middle text-center">Image</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr>
                                            <td class="align-middle text-center">
                                                @if ($service->top_position === $top_position_first->top_position)
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('services.moveDownTop', $service->id) }}"><i class="bi bi-caret-down-fill"></i></a>
                                                @elseif ($service->top_position === $top_position_last->top_position)
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('services.moveUpTop', $service->id) }}"><i class="bi bi-caret-up-fill"></i></a>
                                                @else
                                                    <div class="d-flex flex-column align-items-center">
                                                        <a class="btn btn-sm action-btn view-btn" href="{{ route('services.moveUpTop', $service->id) }}"><i class="bi bi-caret-up-fill"></i></a>
                                                        <br class="mb-3">
                                                        <a class="btn btn-sm action-btn view-btn" href="{{ route('services.moveDownTop', $service->id) }}"><i class="bi bi-caret-down-fill"></i></a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">{{ $service->top_position }}</td>
                                            <td class="align-middle text-center">{{ $service->name }}</td>
                                            <td class="align-middle text-center"><img src="{{ asset('storage/services/' . $service->image_path) }}"
                                                    alt="{{ $service->image_path }}" width="100" class="category-image">
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