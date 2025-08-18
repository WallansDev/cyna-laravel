@extends('layouts.base')

@section('title', 'Créer un utilisateur - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 40%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Créer un utilisateur</span>
                        </div>
                    </div>
                    <div class="card-body pt-3 pb-3" style="margin: auto; width: 95%; background: transparent !important;">
                        <form method="POST" action="{{ route('users.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('users.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
