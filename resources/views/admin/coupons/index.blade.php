@extends('layouts.base')

@section('title', 'Coupons Stripe - ' . $_SOCIETYNAME)

@section('content')
    <div class="container mt-5">
        <h1 class="text-white mb-4" style="text-align: center;">Gestion des coupons Stripe</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="col-sm-12 d-flex justify-content-center">
            <div class="card purple-theme" style="width: 85%" data-bs-theme="dark">
                <div class="purple-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">Créer un coupon</span>
                        <button type="submit" class="btn btn-gold btn-sm">Créer</button>
                    </div>
                </div>
                <div class="card-body m-3">
                    <form method="POST" action="{{ route('admin.coupons.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" placeholder="EX: SPRING24"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="percent">Pourcentage</option>
                                    <option value="amount">Montant fixe</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Valeur</label>
                                <input type="number" name="value" class="form-control" min="0.01" step="0.01"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Devise (si montant fixe)</label>
                                <input type="text" name="currency" class="form-control" placeholder="EUR">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Max utilisations</label>
                                <input type="number" name="max_redemptions" class="form-control" min="1">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Expire le</label>
                                <input type="datetime-local" name="expires_at" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-12 d-flex justify-content-center">
            <div class="card purple-theme" style="width: 85%" data-bs-theme="dark">
                <div class="purple-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span id="card_title">Coupons existants</span>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th class="align-middle text-center">N° coupon</th>
                                <th class="align-middle text-center">Type</th>
                                <th class="align-middle text-center">Valeur</th>
                                <th class="align-middle text-center">Promotion codes actifs</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($coupons as $coupon)
                                <tr>
                                    <td class="align-middle text-center">{{ $coupon->id }}</td>
                                    <td class="align-middle text-center">{{ $coupon->percent_off ? 'Pourcentage' : 'Montant fixe' }}</td>
                                    <td class="align-middle text-center">
                                        @if ($coupon->percent_off)
                                            {{ $coupon->percent_off }}%
                                        @else
                                            {{ number_format(($coupon->amount_off ?? 0) / 100, 2, ',', ' ') }}
                                            {{ strtoupper($coupon->currency ?? 'eur') }}
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        @php
                                            $codes = $couponIdToActiveCodes[$coupon->id] ?? [];
                                        @endphp
                                        @if (empty($codes))
                                            <span class="badge bg-secondary">Aucun</span>
                                        @else
                                            @foreach ($codes as $code)
                                                <span class="badge bg-success">{{ $code }}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                            onsubmit="return confirm('Supprimer/désactiver ce coupon ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm action-btn delete-btn">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun coupon.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
