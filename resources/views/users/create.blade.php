@extends('layouts.base')

@section('template_title')
    {{ __('Create') }} Users
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Users</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('users.store') }}" role="form" enctype="multipart/form-data">
                            @csrf

                            @include('users.form')

                        </form>
                        <a href="{{ url()->previous() }}">Retour en arrière</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
