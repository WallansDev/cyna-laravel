@extends('layouts.base')

@section('title', 'Ordre services top du moment - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <a style="font-size: 30px; text-decoration: underline;" href="{{ route('services.index') }}">Modifier les
                    services</a>

                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Produits top du moment') }}
                            </span>
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
                                        <th>Service</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($services as $service)
                                        <tr>
                                            <td>
                                                @if ($service->top_position === $top_position_first->top_position)
                                                    <br>
                                                    <a href="{{ route('services.moveDownTop', $service->id) }}">🔽</a>
                                                @elseif ($service->top_position === $top_position_last->top_position)
                                                    <a href="{{ route('services.moveUpTop', $service->id) }}">🔼</a>
                                                @else
                                                    <a href="{{ route('services.moveUpTop', $service->id) }}">🔼</a>
                                                    <a href="{{ route('services.moveDownTop', $service->id) }}">🔽</a>
                                                @endif
                                            </td>
                                            <td>{{ $service->top_position }}</td>
                                            <td>{{ $service->name }}</td>
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
