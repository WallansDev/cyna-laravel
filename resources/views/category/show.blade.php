@extends('layouts.base')

@section('title', 'Catégorie ' . $category->name . ' - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <h5>{{ $category->name }}</h5>
        <img src="{{ asset('storage/categories/' . $category->image_path) }}" alt="{{ $category->name }}">
        <p>{{ $category->description }}</p>

        <h5>Services liés à la catégorie {{ $category->name }} :</h5>
        @foreach ($category->services as $service)
            - <a href="{{ route('services.show', $service->id) }}">{{ $service->name }}</a>
            <br>
            <small>{{ $service->description }}</small>
            <br><br>
        @endforeach
    </div>
@endsection
