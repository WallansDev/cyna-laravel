@extends('layouts.base')

@section('title', 'Créer une catégorie - ' . $_SOCIETYNAME)

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Category</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('categories.store') }}" role="form"
                            enctype="multipart/form-data">
                            @csrf

                            @include('category.form')

                        </form>
                        <a href="{{ url()->previous() }}">Retour en arrière</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
