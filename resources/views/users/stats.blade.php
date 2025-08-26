@extends('layouts.base')

@section('title', 'Statistiques utilisateurs - ' . $_SOCIETYNAME)

@section('content')
    <div class="container mt-5">
        <h1 class="text-white mb-4" style="text-align: center;">Statistiques des utilisateurs</h1>

        <div class="row pb-3 pt-3 justify-content-center service-category-card">
            <div class="col-md-4">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Total utilisateurs</h5>
                    <p class="display-6">{{ $totalUsers }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Nouveaux utilisateurs cette semaine</h5>
                    <p class="display-6">{{ $newUsersThisWeek }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Nouveaux utilisateurs cette année</h5>
                    <p class="display-6">
                        {{ \App\Models\User::whereYear('created_at', now()->year)->count() }}
                    </p>
                </div>
            </div>
        </div>
        <br>
        <div class="row pb-3 pt-3 justify-content-center service-category-card">
            <div class="col-md-6">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    @if ($period == 'year')
                        <h5>Évolution du nombre total d’utilisateurs (cette année)</h5>
                    @else
                        <h5>Évolution du nombre total d’utilisateurs (cette semaine)</h5>
                    @endif
                    <div id="userEvolutionChart" style="width:100%;height:400px"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    @if ($period == 'week')
                        <h5>Nouveaux utilisateurs par jour (cette semaine)</h5>
                        <div id="usersByPeriodChart" style="width:100%;height:400px"></div>
                    @else
                        <h5>Nouveaux utilisateurs par mois (cette année)</h5>
                        <div id="usersByPeriodChart" style="width:100%;height:400px"></div>
                    @endif
                </div>
            </div>
            <form method="GET" class=" mt-3 text-center">
                <label for="period" class="me-2 fw-bold text-white">Période :</label>
                <select name="period" id="period" class="form-select d-inline-block w-auto" data-bs-theme="dark"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important; color: white; background-color: #2A213B !important; border: none; text-align: right;"
                    onchange="this.form.submit()">
                    <option value="week" {{ request('period', $period ?? 'year') == 'week' ? 'selected' : '' }}>Semaine
                    </option>
                    <option value="year" {{ request('period', $period ?? 'year') == 'year' ? 'selected' : '' }}>Année
                    </option>
                </select>
            </form>
        </div>
        <br>
        <div class="row pb-3 pt-3 justify-content-center service-category-card">
            <div class="col-md-12">
                <div class="card text-center card-body service-category-card"
                    style="background: linear-gradient(135deg, #372F48, #2A213B) !important;">
                    <h5>Répartition par pays</h5>
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <div id="countryGeoChart" style="width:80%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        const countryData = [
            ['Pays', 'Utilisateurs'],
            @foreach ($countries as $c)
                ["{{ $c->country ?? 'Inconnu' }}", {{ $c->count }}],
            @endforeach
        ];

        // Préparation des données pour le graphique géographique
        // Conversion des noms de pays français en codes ISO ou noms anglais pour Google Charts
        @php
            // Tableau de correspondance minimal FR => EN/ISO (à compléter selon vos besoins)
            $countryMap = [
                'France' => 'France',
                'Allemagne' => 'Germany',
                'Espagne' => 'Spain',
                'Italie' => 'Italy',
                'États-Unis' => 'United States',
                'Royaume-Uni' => 'United Kingdom',
                'Belgique' => 'Belgium',
                'Suisse' => 'Switzerland',
                'Canada' => 'Canada',
                'Maroc' => 'Morocco',
                'Tunisie' => 'Tunisia',
                'Algérie' => 'Algeria',
                'Portugal' => 'Portugal',
                'Pays-Bas' => 'Netherlands',
                'Luxembourg' => 'Luxembourg',
                'Chine' => 'China',
                'Japon' => 'Japan',
                'Brésil' => 'Brazil',
                'Russie' => 'Russia',
                // Ajoutez d'autres pays selon vos besoins
            ];
        @endphp
        const countryGeoData = [
            ['Country', 'Utilisateurs'],
            @foreach ($countries as $c)
                @php
                    $countryEn = $countryMap[$c->country ?? ''] ?? ($c->country ?? 'Unknown');
                @endphp
                    ["{{ $countryEn }}", {{ $c->count }}],
            @endforeach
        ];

        // Données dynamiques selon la période
        @if ($period == 'week')
            const usersByPeriodData = [
                ['Date', 'Nouveaux utilisateurs'],
                <?php
                $dateMap = [];
                foreach ($usersByDay as $u) {
                    $dateMap[$u->date] = $u->count;
                }
                $start = \Carbon\Carbon::now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $start->copy()->addDays($i);
                    $dateStr = $date->format('Y-m-d');
                    $label = $date->format('d/m');
                    $count = max(0, $dateMap[$dateStr] ?? 0);
                    echo "['$label', $count],\n";
                }
                ?>
            ];
            const userEvolutionData = [
                ['Date', 'Total utilisateurs'],
                <?php
                $start = \Carbon\Carbon::now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $start->copy()->addDays($i)->endOfDay();
                    $label = $date->format('d/m');
                    $count = \App\Models\User::where('created_at', '<=', $date)->count();
                    echo "['$label', $count],\n";
                }
                ?>
            ];
        @else
            const usersByPeriodData = [
                ['Mois', 'Nouveaux utilisateurs'],
                <?php
                $months = [];
                $currentMonth = (int) date('n');
                $currentYear = date('Y');
                for ($i = 1; $i <= $currentMonth; $i++) {
                    $monthStr = $currentYear . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $months[$monthStr] = 0;
                }
                foreach ($usersByMonth as $u) {
                    $months[$u->month] = $u->count;
                }
                foreach ($months as $month => $count) {
                    $parts = explode('-', $month);
                    $label = $parts[1] . '/' . $parts[0];
                    echo "['$label', $count],\n";
                }
                ?>
            ];
            const userEvolutionData = [
                ['Mois', 'Total utilisateurs'],
                <?php
                $currentMonth = (int) date('n');
                foreach ($userEvolution as $evo) {
                    $parts = explode('-', $evo['month']);
                    if ((int) $parts[1] <= $currentMonth) {
                        $label = $parts[1] . '/' . $parts[0];
                        echo "['$label', {$evo['count']}],\n";
                    }
                }
                ?>
            ];
        @endif

        google.charts.load('current', {
            'packages': ['corechart', 'geochart'],
            'mapsApiKey': 'AIzaSyD-EXEMPLE-KEY-REMPLACEZ'
        });
        google.charts.setOnLoadCallback(drawCharts);

        function drawCharts() {
            // Carte géographique des utilisateurs par pays
            var countryGeoDataTable = google.visualization.arrayToDataTable(countryGeoData);
            var countryGeoOptions = {
                colorAxis: {
                    colors: ['#e1cdfe', '#6D08FD']
                },
                backgroundColor: 'none',
                datalessRegionColor: '#f8f9fa',
                displayMode: 'auto',
                legend: {
                    position: 'none',
                    textStyle: {
                        color: 'white',
                        bold: false,
                        fontSize: 12,
                        fontName: 'Arial'
                    }
                }
            };
            var countryGeoChart = new google.visualization.GeoChart(document.getElementById('countryGeoChart'));
            countryGeoChart.draw(countryGeoDataTable, countryGeoOptions);

            var usersByPeriodTable = google.visualization.arrayToDataTable(usersByPeriodData);
            var usersByPeriodOptions = {
                backgroundColor: 'none',
                legend: {
                    position: 'none',
                    textStyle: {
                        color: 'white'
                    }
                },
                chartArea: {
                    width: '90%',
                    height: '75%' // réduit pour laisser de la place aux labels
                },
                colors: ['#6D08FD'],
                hAxis: {
                    title: '{{ $period == 'year' ? 'Mois' : 'Date' }}',
                    titleTextStyle: {
                        color: 'white',
                        bold: true,
                        fontSize: 14,
                        italic: false
                    },
                    textStyle: {
                        color: 'white',
                        italic: false,
                        fontSize: 12
                    },
                    showTextEvery: 1,
                    gridlines: {
                        color: 'white'
                    }
                },
                vAxis: {
                    title: 'Nouveaux utilisateurs',
                    titleTextStyle: {
                        color: 'white',
                        bold: true,
                        fontSize: 14,
                        italic: false
                    },
                    textStyle: {
                        color: 'white',
                        italic: false
                    },
                    minValue: 0,
                    gridlines: {
                        color: '#fff',
                        count: -1,
                        interval: 1
                    },
                    minorGridlines: {
                        color: 'none'
                    },
                    viewWindow: {
                        min: 0
                    }
                }
            };
            var usersByPeriodChart = new google.visualization.ColumnChart(document.getElementById('usersByPeriodChart'));
            usersByPeriodChart.draw(usersByPeriodTable, usersByPeriodOptions);

            var userEvolutionTable = google.visualization.arrayToDataTable(userEvolutionData);
            var userEvolutionOptions = {
                backgroundColor: 'none',
                legend: {
                    position: 'none',
                    textStyle: {
                        color: 'white'
                    }
                },
                chartArea: {
                    width: '90%',
                    height: '75%' // réduit pour laisser de la place aux labels
                },
                colors: ['#6D08FD'],
                hAxis: {
                    title: '{{ $period == 'year' ? 'Mois' : 'Date' }}',
                    titleTextStyle: {
                        color: 'white',
                        bold: true,
                        fontSize: 14,
                        italic: false
                    },
                    textStyle: {
                        color: 'white',
                        italic: false,
                        fontSize: 12
                    },
                    gridlines: {
                        color: 'white'
                    }
                },
                vAxis: {
                    title: 'Total utilisateurs',
                    titleTextStyle: {
                        color: 'white',
                        bold: true,
                        fontSize: 14,
                        italic: false
                    },
                    textStyle: {
                        color: 'white',
                        italic: false
                    },
                    minValue: 0,
                    gridlines: {
                        color: '#fff',
                        count: -1,
                        interval: 1
                    },
                    minorGridlines: {
                        color: 'none'
                    }
                },
                series: {
                    0: {
                        lineWidth: 5
                    } // Augmente l'épaisseur de la courbe
                }
            };
            var userEvolutionChart = new google.visualization.LineChart(document.getElementById('userEvolutionChart'));
            userEvolutionChart.draw(userEvolutionTable, userEvolutionOptions);
        }

        window.addEventListener('resize', drawCharts);
    </script>
@endpush
