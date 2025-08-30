@extends('layouts.base')

@section('title', 'Tableau de bord des ventes - ' . $_SOCIETYNAME)

@section('content')
    <style>
        table, thead, tbody, tr, th, td {
            background: transparent !important;
        }
        th, td {
            color: #fff !important;
        }
    </style>
    <div class="container mt-5">
        <h1 class="text-white mb-4" style="text-align: center;">Tableau de bord des ventes</h1>
        <div class="mt-3 mb-3">
            <a href="{{ route('orders.admin') }}" class="btn btn-info">Afficher en tableau</a>
        </div>
        <div class="row pb-3 pt-3 justify-content-center service-category-card">
            <div class="col-md-4">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Chiffre d'affaires total</h5>
                    <p class="display-6">{{ number_format($totalRevenue, 2, ',', ' ') }} €</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Nombre de commandes</h5>
                    <p class="display-6">{{ $totalOrders }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Panier moyen</h5>
                    <p class="display-6">{{ number_format($avgOrderValue, 2, ',', ' ') }} €</p>
                </div>
            </div>
        </div>
        <br>
        <div class="row pb-3 pt-3 justify-content-center service-category-card">
            <div class="col-md-12">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>30 derniers jours</h5>
                    <div class="card-body" style="background: transparent !important;">
                        <canvas id="salesChart" height="90"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row pb-3 pt-3 justify-content-center service-category-card">
            <div class="col-md-6">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Chiffre d'affaires par mois (12 derniers mois)</h5>
                    <div class="card-body table-responsive" style="background: transparent !important;">
                        <table class="table" style="background: transparent !important;">
                            <thead style="background: transparent !important;">
                                <tr style="background: transparent !important;">
                                    <th>Mois</th>
                                    <th>Chiffre d'affaires</th>
                                </tr>
                            </thead>
                            <tbody style="background: transparent !important;">
                                @forelse (collect($revenueByMonth)->sortKeys() as $month => $amount)
                                    <tr style="background: transparent !important;">
                                        <td>{{ $month }}</td>
                                        <td>{{ number_format($amount, 2, ',', ' ') }} €</td>
                                    </tr>
                                @empty
                                    <tr style="background: transparent !important;">
                                        <td colspan="2" class="text-center">Aucune donnée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Classement des produits les plus vendus</h5>
                    <div class="card-body table-responsive" style="background: transparent !important;">
                        <table class="table" style="background: transparent !important;">
                            <thead style="background: transparent !important;">
                                <tr style="background: transparent !important;">
                                    <th>Rang</th>
                                    <th>Produit</th>
                                    <th>Quantité vendue</th>
                                    <th>Chiffre d'affaires</th>
                                </tr>
                            </thead>
                            <tbody style="background: transparent !important;">
                                @forelse ($topProducts as $index => $product)
                                    <tr style="background: transparent !important;">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product['name'] }}</td>
                                        <td>{{ $product['quantity'] }}</td>
                                        <td>{{ number_format($product['revenue'], 2, ',', ' ') }} €</td>
                                    </tr>
                                @empty
                                    <tr style="background: transparent !important;">
                                        <td colspan="4" class="text-center">Aucune donnée.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
                        position: 'top',
                        labels: {
                            color: 'white' // légende en blanc
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: 'white' // texte axe X en blanc
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.2)' // lignes en blanc transparent
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            color: 'white', // texte axe Y en blanc
                            callback: (value) => value + ' €'
                        },
                        grid: {
                            color: 'rgba(255,255,255,0.2)' // lignes blanches
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        ticks: {
                            color: 'white' // texte axe Y1 en blanc
                        },
                        grid: {
                            drawOnChartArea: false,
                            color: 'rgba(255,255,255,0.2)' // optionnel si tu veux aussi un peu visible
                        }
                    }
                }
            }
        });
    </script>

@endsection
