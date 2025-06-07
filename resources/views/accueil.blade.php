@extends('layouts.base')

@section('title', "Page d'accueil - ")

@section('content')
    <a href="{{ route('carousel.index') }}">Modifier le carousel</a>
    <br>
    <a href="{{ route('categories.index') }}">Affichage catégories</a>
    <br>
    <a href="{{ route('service.topProducts') }}">Affichage top services</a>
@endsection
