@extends('layouts.base')

@section('title', 'Catégories - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 5em;">
        <div class="row">
            <div class="col-sm-12">
                <div class="card purple-theme">
                    <div class="card-header purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Categories') }}</span>
                            <a href="{{ route('categories.create') }}" class="btn btn-gold btn-sm">
                                {{ __('Create New') }}
                            </a>
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
                                                    <a href="{{ route('categories.down', $category->id) }}">🔽</a>
                                                @elseif ($category->position === $category_last->position)
                                                    <a href="{{ route('categories.up', $category->id) }}">🔼</a>
                                                @else
                                                    <a href="{{ route('categories.up', $category->id) }}">🔼</a>
                                                    <a href="{{ route('categories.down', $category->id) }}">🔽</a>
                                                @endif
                                            </td>
                                            <td>{{ $category->position }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>
                                                <img src="{{ asset('storage/categories/' . $category->image_path) }}"
                                                     alt="{{ $category->image_path }}"
                                                     width="100"
                                                     class="category-image">
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
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                                    <a class="btn btn-sm action-btn view-btn"
                                                       href="{{ route('categories.show', $category->id) }}">👁️</a>
                                                    <a class="btn btn-sm action-btn edit-btn"
                                                       href="{{ route('categories.edit', $category->id) }}">✏️</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm action-btn delete-btn"
                                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                                        🗑️
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center p-3">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
