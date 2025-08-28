@extends('layouts.base')

@section('title', 'Mes adresses de facturation - ' . $_SOCIETYNAME)

@section('content')
    <div class="container py-5">
        <h1 class="text-white mb-4" style="text-align: center;">Mes adresses de facturation</h1>
        @if (session('success'))
            <br>
            <div class="alert alert-success align-middle text-center fw-bold"
                style="margin:auto; width: 30%; background-color: #28a745; color: white;">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <br>
            <div class="alert alert-danger align-middle text-center fw-bold"
                style="margin:auto; width: 30%; background-color: #dc3545; color: white;">
                {{ session('error') }}
            </div>
        @endif
        <br>
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden"
            style="background: linear-gradient(135deg, var(--primary-color), #1b1724) !important;">
            <div class="card-body px-4 py-4">
                <div class="card-body text-end">
                    @if ($addresses->count() > 0)
                        <div class="row text-start">
                            @foreach ($addresses as $address)
                                <div class="col-md-6 mb-3" data-bs-theme="dark">
                                    <div class="card h-100">
                                        <div class="card-body category-title-inline" style="border-radius: 0;">
                                            <h6 class="card-title align-middle">
                                                {{ $address->address_line1 . ', ' . $address->postal_code . ' ' . $address->city }}
                                            </h6>
                                            <h6 class="card-title">{{ $address->address_line2 }}</h6>
                                            <h6 class="card-title">{{ $address->country }}</h6>
                                            <p>{{ $address->phone }}</p>
                                        </div>
                                        <div class="card-footer service-category-title">
                                            <div class="align-middle text-end" role="group">
                                                <button type="button" class="btn btn-sm action-btn edit-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editAddressModal{{ $address->id }}">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                <form action="{{ route('billing-addresses.destroy', $address->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm action-btn delete-btn"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de modification -->
                                <div class="modal fade" id="editAddressModal{{ $address->id }}" tabindex="-1"
                                    data-bs-theme="dark">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header purple-header">
                                                <h5 class="modal-title">Modifier l'adresse</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('billing-addresses.update', $address->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="address_line1" class="form-label fw-bold">Adresse ligne
                                                            1
                                                            *</label>
                                                        <input type="text" class="form-control" id="address_line1"
                                                            name="address_line1" value="{{ $address->address_line1 }}"
                                                            required placeholder="Adresse ligne 1">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="address_line2" class="form-label fw-bold">Adresse ligne
                                                            2</label>
                                                        <input type="text" class="form-control" id="address_line2"
                                                            name="address_line2" value="{{ $address->address_line2 }}"
                                                            placeholder="Adresse ligne 2">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="city" class="form-label fw-bold">Ville *</label>
                                                        <input type="text" class="form-control" id="city"
                                                            name="city" value="{{ $address->city }}" required
                                                            placeholder="Ville">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="postal_code" class="form-label fw-bold">Code postal
                                                            *</label>
                                                        <input type="text" class="form-control" id="postal_code"
                                                            name="postal_code" value="{{ $address->postal_code }}" required
                                                            placeholder="Code postal">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="country" class="form-label fw-bold">Pays *</label>
                                                        <input type="text" class="form-control" id="country"
                                                            name="country" value="{{ $address->country }}" required
                                                            placeholder="Pays">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label fw-bold">Téléphone *</label>
                                                        <input type="text" class="form-control" id="phone"
                                                            name="phone" value="{{ $address->phone }}" required
                                                            placeholder="Téléphone">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="col-md-12 mt20 mt-2 text-center">
                                                        <button type="submit" class="btn btn-success">Modifier</button>
                                                        <button type="button" class="ms-3 btn btn-primary"
                                                            data-bs-dismiss="modal">Annuler</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-gold btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addAddressModal">
                            Ajouter une adresse
                        </button>
                    @else
                        <div class="text-center py-4">
                            <p class="text-white fs-4 mb-4">Aucune adresse de facturation enregistrée.</p>
                            <button type="button" class="btn btn-gold btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addAddressModal">
                                Ajouter votre première adresse
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" data-bs-theme="dark">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header purple-header">
                    <h5 class="modal-title">Ajouter une adresse de facturation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('billing-addresses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="address_line1" class="form-label fw-bold">Adresse ligne 1 *</label>
                            <input type="text" class="form-control" id="address_line1" name="address_line1" required
                                placeholder="Adresse ligne 1">
                        </div>
                        <div class="mb-3">
                            <label for="address_line2" class="form-label fw-bold">Adresse ligne 2</label>
                            <input type="text" class="form-control" id="address_line2" name="address_line2"
                                placeholder="Adresse ligne 2">
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label fw-bold">Ville *</label>
                            <input type="text" class="form-control" id="city" name="city" required
                                placeholder="Ville">
                        </div>
                        <div class="mb-3">
                            <label for="postal_code" class="form-label fw-bold">Code postal *</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required
                                placeholder="Code postal">
                        </div>
                        <div class="mb-3">
                            <label for="country" class="form-label fw-bold">Pays *</label>
                            <input type="text" class="form-control" id="country" name="country" required
                                placeholder="Pays">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">Téléphone *</label>
                            <input type="text" class="form-control" id="phone" name="phone" required
                                placeholder="Téléphone">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12 mt20 mt-2 text-center">
                            <button type="submit" class="btn btn-success">Ajouter</button>
                            <button type="button" class="ms-3 btn btn-primary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
