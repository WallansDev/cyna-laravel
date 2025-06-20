@extends('layouts.base')

@section('title', 'Modifier accueil - ')

@section('content')
    <a href="{{ route('carousel.index') }}">Modifier le carousel</a>
    <br>
    <a href="{{ route('categories.viewAdmin') }}">Affichage catégories</a>
    <br>
    <a href="{{ route('services.topProducts') }}">Affichage top services</a>
@endsection
