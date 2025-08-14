@extends('layouts.base')

@section('title', 'Adresses de facturation - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Mes adresses de facturation</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            Ajouter une adresse
                        </button>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($addresses->count() > 0)
                            <div class="row">
                                @foreach ($addresses as $address)
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6 class="card-title">
                                                    {{ $address->address_line1 . ', ' . $address->postal_code . ' ' . $address->city }}
                                                </h6>
                                                <h6 class="card-title">{{ $address->address_line2 }}</h6>
                                                <h6 class="card-title">{{ $address->country }}</h6>
                                                <p>{{ $address->phone }}</p>
                                            </div>
                                            <div class="card-footer">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editAddressModal{{ $address->id }}">
                                                        <i style="color: darkorange;" class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('billing-addresses.destroy', $address->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')">
                                                            <i style="color: darkred;" class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal de modification -->
                                    <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Modifier l'adresse</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('billing-addresses.update', $address->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="address_line1" class="form-label">Adresse ligne 1
                                                                *</label>
                                                            <input type="text" class="form-control" id="address_line1"
                                                                name="address_line1" value="{{ $address->address_line1 }}"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="address_line2" class="form-label">Adresse ligne
                                                                2</label>
                                                            <input type="text" class="form-control" id="address_line2"
                                                                name="address_line2" value="{{ $address->address_line2 }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="city" class="form-label">Ville *</label>
                                                            <input type="text" class="form-control" id="city"
                                                                name="city" value="{{ $address->city }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="postal_code" class="form-label">Code postal
                                                                *</label>
                                                            <input type="text" class="form-control" id="postal_code"
                                                                name="postal_code" value="{{ $address->postal_code }}"
                                                                required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="country" class="form-label">Pays *</label>
                                                            <input type="text" class="form-control" id="country"
                                                                name="country" value="{{ $address->country }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="phone" class="form-label">Téléphone *</label>
                                                            <input type="text" class="form-control" id="phone"
                                                                name="phone" value="{{ $address->phone }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary">Modifier</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-muted">Aucune adresse de facturation enregistrée.</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addAddressModal">
                                    Ajouter votre première adresse
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout -->
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une adresse de facturation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('billing-addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="address_line1" class="form-label">Adresse ligne 1 *</label>
                            <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                        </div>
                        <div class="mb-3">
                            <label for="address_line2" class="form-label">Adresse ligne 2</label>
                            <input type="text" class="form-control" id="address_line2" name="address_line2">
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">Ville *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Code postal *</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label">Pays *</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
