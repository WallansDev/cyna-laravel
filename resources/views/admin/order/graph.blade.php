@extends('layouts.base')

@section('title', 'Tableau de bord des ventes - ' . $_SOCIETYNAME)

@section('content')
    <div class="container">
        <h2>Tableau de bord des ventes</h2>
        <div class="mt-3 mb-3">
            <a href="{{ route('orders.admin') }}" class="btn btn-info">Afficher en tableau</a>
        </div>
        <div class="row my-3">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Chiffre d'affaires total</h6>
                        <h3>{{ number_format($totalRevenue, 2, ',', ' ') }} €</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Nombre de commandes</h6>
                        <h3>{{ $totalOrders }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Panier moyen</h6>
                        <h3>{{ number_format($avgOrderValue, 2, ',', ' ') }} €</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">30 derniers jours</div>
            <div class="card-body">
                <canvas id="salesChart" height="90"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Chiffre d'affaires par mois (12 derniers mois)</div>
            <div class="card-body table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>CA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse (collect($revenueByMonth)->sortKeys() as $month => $amount)
                            <tr>
                                <td>{{ $month }}</td>
                                <td>{{ number_format($amount, 2, ',', ' ') }} €</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center">Aucune donnée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const days = @json($days);
        const revenueData = @json($chartRevenueData);
        const ordersData = @json($chartOrdersData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: days,
                datasets: [{
                        label: "Chiffre d'affaires (€)",
                        data: revenueData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.3,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Commandes',
                        data: ordersData,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.3,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                stacked: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: (value) => value + ' €'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                    }
                }
            }
        });
    </script>
@endsection
