@extends('layouts.base')

@section('title', 'Catégories - ' . $_SOCIETYNAME)

@section('content')

    <div class="container">
        <h1>Les catégories</h1>
        @foreach ($categories as $category)
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8">
                    <div class="card mb-3">
                        {{-- <img src="{{ asset('storage/services/' . $service->image_path) }}" class="card-img-top"
                            alt="{{ $service->name }}"> --}}
                        <div class="card-body">
                            <h5 style="color:black;">{{ $category->name }}</h5>
                            <p class="card-text" style="color:black;">{{ $category->description }}</p>
                            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-success"
                                style="float: right">Afficher
                                la catégorie</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
