@extends('layouts.base')

@section('title', 'Support - ' . $_SOCIETYNAME)

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 40%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Créer un ticket</span>
                        </div>
                    </div>
                    <div class="card-body pt-3 pb-3" style="margin: auto; width: 95%; background: transparent !important;">
                        <form method="POST" action="{{ route('tickets.store') }}">
                            @csrf
                            <div>
                                <label for="name" class="form-label fw-bold">Objet</label>
                                <input type="text" class="form-control" name="subject" id="subject" required>
                            </div>
                            <br>
                            <div>
                                <label for="message" class="form-label fw-bold">Message</label>
                                <textarea name="message" class="form-control" id="message" rows="5" required></textarea>
                            </div>
                            <br>
                            <div class="col-md-12 mt20 mt-2 text-center">
                                <button type="submit" class="btn btn-success">{{ __('Envoyer') }}</button>
                                <a href="{{ url()->previous() }}" class="ms-3 btn btn-primary">Retour en arrière</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
