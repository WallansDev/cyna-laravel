@extends('layouts.base')

@section('title', 'Catégories - ' . $_SOCIETYNAME)

@section('content')
    <div class="container-fluid" style="margin-top: 2em;">
        <div class="row">
            <div class="col-sm-12 d-flex justify-content-center">
                <div class="card purple-theme" style="width: 85%" data-bs-theme="dark">
                    <div class="purple-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Categories') }}</span>
                            <a href="{{ route('categories.create') }}" class="btn btn-gold btn-sm">
                                {{ __('Nouveau') }}
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
                                        <th class="align-middle text-center">Position</th>
                                        <th class="align-middle text-center">Titre</th>
                                        <th class="align-middle text-center">Image</th>
                                        <th class="align-middle">Description</th>
                                        <th class="align-middle">Services associés</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td class="align-middle text-center">
                                                @if ($category->position === $category_first->position)
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('categories.down', $category->id) }}">
                                                        <i class="bi bi-caret-down-fill"></i>
                                                    </a>
                                                @elseif ($category->position === $category_last->position)
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('categories.up', $category->id) }}"><i class="bi bi-caret-up-fill"></i></a>
                                                @else
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('categories.up', $category->id) }}"><i class="bi bi-caret-up-fill"></i></a>
                                                    <br class="mb-3">
                                                    <a class="btn btn-sm action-btn view-btn" href="{{ route('categories.down', $category->id) }}"><i class="bi bi-caret-down-fill"></i></a>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">{{ $category->position }}</td>
                                            <td class="align-middle text-center">{{ $category->name }}</td>
                                            <td class="align-middle text-center">
                                                <img src="{{ asset('storage/categories/' . $category->image_path) }}"
                                                     alt="{{ $category->image_path }}"
                                                     width="100"
                                                     class="category-image">
                                            </td>
                                            <td class="align-middle">{{ $category->description }}</td>
                                            <td class="align-middle">
                                                @forelse ($category->services as $service)
                                                    <span class="badge bg-purple">{{ $service->name }}</span>
                                                @empty
                                                    <span class="badge bg-purple">Pas de service</span>
                                                @endforelse
                                            </td>
                                            <td class="align-middle text-center">
                                                <form class="d-flex flex-column align-items-center gap-2" action="{{ route('categories.destroy', $category->id) }}" method="POST">
                                                    <a class="btn btn-sm action-btn view-btn"
                                                       href="{{ route('categories.show', $category->id) }}"><i class="bi bi-eye-fill"></i></a>
                                                    <a class="btn btn-sm action-btn edit-btn"
                                                       href="{{ route('categories.edit', $category->id) }}"><i class="bi bi-pencil-fill"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm action-btn delete-btn"
                                                            onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center ">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
