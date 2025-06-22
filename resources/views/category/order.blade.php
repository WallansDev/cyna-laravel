@extends('layouts.base')

@section('title', 'Catégories - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 5em;">
        <div class="row">
            <div class="col-sm-12">
                <div class="card purple-theme">
                    <div class="card-header purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Categories') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('categories.create') }}" class="btn purple-btn-primary btn-sm float-right"
                                    data-placement="left">
                                    {{ __('Create New') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-dark">
                                <thead class="thead">
                                    <tr>
                                        <th></th>
                                        <th>Position</th>
                                        <th>Title</th>
                                        <th>Image</th>

                                        {{-- <th></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                @if ($category->position === $category_first->position)
                                                    <br>
                                                    <a href="{{ route('categories.down', $category->id) }}">🔽</a>
                                                @elseif ($category->position === $category_last->position)
                                                    <a href="{{ route('categories.up', $category->id) }}">🔼</a>
                                                @else
                                                    <a href="{{ route('categories.up', $category->id) }}">🔼</a>
                                                    <a href="{{ route('categories.down', $category->id) }}">🔽</a>

                                            </td>
                                            <td>{{ $category->position }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td><img src="{{ asset('storage/categories/' . $category->image_path) }}"
                                                    alt="{{ $category->image_path }}" width="20%" class="category-image">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection