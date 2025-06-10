@extends('layouts.base')

@section('title', 'Dashboard - ' . $_SOCIETYNAME)

@section('content')
    <h2>Page de compte</h2>
    <a href="{{ route('profile.edit') }}">Modifier informations du compte</a>
    <br>
    <a href="{{ route('password.edit') }}">Modifier mot de passe</a>
    <br>
@endsection
