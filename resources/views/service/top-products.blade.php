@extends('layouts.base')

@section('title', 'Ordre services top du moment - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 5em;">
        <div class="row">
            <div class="col-sm-12">
                <div class="card purple-theme">
                    <div class="card-header purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Produits top du moment') }}
                            </span>
                            
                            <div class="float-right">
                                <a href="{{ route('services.index') }}" class="btn purple-btn-primary btn-sm float-right"
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
                                                    <a href="{{ route('service.moveDownTop', $service->id) }}"><i class="fa-solid fa-square-caret-down"></i></a>
                                                @elseif ($service->top_position === $top_position_last->top_position)
                                                    <a href="{{ route('service.moveUpTop', $service->id) }}"><i class="fa-solid fa-square-caret-up"></i></a>
                                                @else
                                                    <a href="{{ route('service.moveUpTop', $service->id) }}"><i class="fa-solid fa-square-caret-up"></i></a>
                                                    <a href="{{ route('service.moveDownTop', $service->id) }}"><i class="fa-solid fa-square-caret-down"></i></a>
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