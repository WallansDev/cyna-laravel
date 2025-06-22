@extends('layouts.base')

@section('title', $service->name . ' - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <h5>{{ $service->name }}</h5>
        <img src="{{ asset('storage/services/' . $service->image_path) }}" alt="{{ $service->name }}">
        <p>{{ $service->description }}</p>

        @if ($service->availbility)
            <a href="#" class="btn btn-success" style="float: right">Acheter</a>
        @else
            <a href="#" class="btn btn-danger" style="float: right" onclick="return false;">Temporairement
                indisponible</a>
        @endif
    </div>
@endsection
