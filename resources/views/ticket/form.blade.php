@extends('layouts.base')

@section('title', 'Support - ' . $_SOCIETYNAME)

@section('content')
    <h2>Support</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('support.submit') }}">
        @csrf
        <div>
            <label for="subject">Objet</label>
            <input type="text" name="subject" id="subject" required>
        </div>
        <div>
            <label for="message">Message</label>
            <textarea name="message" id="message" rows="5" required></textarea>
        </div>
        <button type="submit">Envoyer</button>
    </form>
@endsection
