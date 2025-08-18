@extends('layouts.base')

@section('title', 'Modifier une catégorie - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 40%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Modifier une catégorie</span>
                        </div>
                    </div>
                     <div class="card-body pt-3 pb-3" style="margin: auto; width: 95%; background: transparent !important;">
                        <form method="POST" action="{{ route('categories.update', $category->id) }}" role="form"
                            enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('category.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
