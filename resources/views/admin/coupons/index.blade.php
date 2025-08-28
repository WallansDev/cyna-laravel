@extends('layouts.base')

@section('title', 'Coupons Stripe - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <h2>Gestion des coupons Stripe</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-header">Créer un coupon</div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.coupons.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Code</label>
                            <input type="text" name="code" class="form-control" placeholder="EX: SPRING24" required>
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
                            <input type="text" name="currency" class="form-control" placeholder="eur">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Max utilisations</label>
                            <input type="number" name="max_redemptions" class="form-control" min="1">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Expire le</label>
                            <input type="datetime-local" name="expires_at" class="form-control">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Créer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Coupons existants</div>
            <div class="card-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Coupon ID</th>
                            <th>Type</th>
                            <th>Valeur</th>
                            <th>Promotion codes actifs</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon->id }}</td>
                                <td>{{ $coupon->percent_off ? 'Pourcentage' : 'Montant fixe' }}</td>
                                <td>
                                    @if ($coupon->percent_off)
                                        {{ $coupon->percent_off }}%
                                    @else
                                        {{ number_format(($coupon->amount_off ?? 0) / 100, 2, ',', ' ') }}
                                        {{ strtoupper($coupon->currency ?? 'eur') }}
                                    @endif
                                </td>
                                <td>
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
                                <td>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                        onsubmit="return confirm('Supprimer/désactiver ce coupon ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Supprimer</button>
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
@endsection
