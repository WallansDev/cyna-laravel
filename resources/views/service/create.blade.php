@extends('layouts.base')

@section('title', 'Créer un service - ' . $_SOCIETYNAME)

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Service</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('services.store') }}" role="form"
                            enctype="multipart/form-data">
                            @csrf

                            @include('service.form')

                        </form>
                        <a href="{{ url()->previous() }}">Retour en arrière</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
