@extends('layouts.base')

@section('title', 'Catégories - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Categories') }}
                            </span>

                            <div class="float-right">
                                <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm float-right"
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

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th></th>
                                        <th>Position</th>
                                        <th>Title</th>
                                        <th>Image Path</th>
                                        <th>Description</th>
                                        <th>Services associés</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                @if ($category->position === $category_first->position)
                                                    <br>
                                                    <a href="{{ route('category.down', $category->id) }}">🔽</a>
                                                @elseif ($category->position === $category_last->position)
                                                    <a href="{{ route('category.up', $category->id) }}">🔼</a>
                                                @else
                                                    <a href="{{ route('category.up', $category->id) }}">🔼</a>
                                                    <a href="{{ route('category.down', $category->id) }}">🔽</a>
                                                @endif
                                            </td>
                                            <td>{{ $category->position }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td><img src="{{ asset('storage/categories/' . $category->image_path) }}"
                                                    alt="{{ $category->image_path }}" width="20%">
                                            </td>
                                            <td>{{ $category->description }}</td>
                                            <td>
                                                @forelse ($category->services as $service)
                                                    <span class="badge bg-secondary">{{ $service->name }}</span>

                                                @empty
                                                    Pas de service
                                                @endforelse
                                            </td>
                                            <td>
                                                <form action="{{ route('categories.destroy', $category->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm btn-primary "
                                                        href="{{ route('categories.show', $category->id) }}">👁️</a>
                                                    <a class="btn btn-sm btn-success"
                                                        href="{{ route('categories.edit', $category->id) }}">✏️</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">🗑️</button>
                                                </form>
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
