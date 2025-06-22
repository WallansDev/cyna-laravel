@extends('layouts.base')

@section('title', 'Les services - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <h1>Les services</h1>
        @foreach ($services as $service)
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card mb-3">
                        {{-- <img src="{{ asset('storage/services/' . $service->image_path) }}" class="card-img-top"
                            alt="{{ $service->name }}"> --}}
                        <div class="card-body">
                            <h5 style="color:black;">{{ $service->name }}</h5>
                            <p class="card-text" style="color:black;">{{ $service->description }}</p>
                            @if ($service->availbility)
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-success"
                                    style="float: right">Afficher
                                    le service</a>
                            @else
                                <a href="{{ route('services.show', $service->id) }}" class="btn btn-danger"
                                    style="float: right">Temporairement
                                    indisponible</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
