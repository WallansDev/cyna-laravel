@extends('layouts.base')

@section('title', 'Catégories - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid"  style="margin-top: 5em;">
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
                            <table class="table table-striped table-hover table-dark">
                                <thead>
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
                                                    <a href="{{ route('category.down', $category->id) }}"><i class="fa-solid fa-square-caret-down"></i></a>
                                                @elseif ($category->position === $category_last->position)
                                                    <a href="{{ route('category.up', $category->id) }}"><i class="fa-solid fa-square-caret-up"></i></a>
                                                @else
                                                    <a href="{{ route('category.up', $category->id) }}"><i class="fa-solid fa-square-caret-up"></i></a>
                                                    <a href="{{ route('category.down', $category->id) }}"><i class="fa-solid fa-square-caret-down"></i></a>
                                                @endif
                                            </td>
                                            <td>{{ $category->position }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <img src="{{ asset('storage/categories/' . $category->image_path) }}"
                                                    alt="{{ $category->image_path }}" width="20%" class="category-image">
                                            </td>
                                            <td>{{ $category->description }}</td>
                                            <td>
                                                @forelse ($category->services as $service)
                                                    <span class="badge bg-purple">{{ $service->name }}</span>
                                                @empty
                                                    <span class="text-muted">Pas de service</span>
                                                @endforelse
                                            </td>
                                            <td>
                                                <form action="{{ route('categories.destroy', $category->id) }}"
                                                    method="POST">
                                                    <a class="btn btn-sm view-btn action-btn"
                                                        href="{{ route('categories.show', $category->id) }}"><i class="fa-solid fa-eye"></i></a>
                                                    <a class="btn btn-sm edit-btn action-btn"
                                                        href="{{ route('categories.edit', $category->id) }}"><i class="fa-solid fa-pencil"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn delete-btn btn-sm action-btn"
                                                        onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa-solid fa-trash"></i></button>
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