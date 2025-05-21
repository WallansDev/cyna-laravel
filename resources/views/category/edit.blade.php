@extends('layouts.base')

@section('title', 'Modifier une catégorie - ' . $_SOCIETYNAME)

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Category</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('categories.update', $category->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
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
