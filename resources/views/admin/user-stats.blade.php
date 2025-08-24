@extends('layouts.base')

@section('title', 'Statistiques utilisateurs - ' . $_SOCIETYNAME)

@section('content')
<div class="container mt-5">
    <h1 class="text-white mb-4" style="text-align: center;">Statistiques des utilisateurs</h1>

    <form method="GET" class="mb-4 text-center">
        <label for="period" class="me-2 fw-bold text-white">Période :</label>
        <select name="period" id="period" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
            <option value="week" {{ request('period', $period ?? 'year') == 'week' ? 'selected' : '' }}>Semaine</option>
            <option value="year" {{ request('period', $period ?? 'year') == 'year' ? 'selected' : '' }}>Année</option>
        </select>
    </form>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body bg-dark text-white">
                    <h5>Total utilisateurs</h5>
                    <p class="display-6">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body bg-dark text-white">
                    <h5>Nouveaux cette semaine</h5>
                    <p class="display-6">{{ $newUsersThisWeek }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body bg-dark text-white">
                    <h5>Nouveaux cette année</h5>
                    <p class="display-6">
                        {{ \App\Models\User::whereYear('created_at', now()->year)->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Répartition par pays</h5>
            <div id="countryChart" style="width:100%;height:400px"></div>
        </div>
        <div class="col-md-6">
            @if($period == 'week')
                <h5>Nouveaux utilisateurs par jour (cette semaine)</h5>
                <div id="usersByPeriodChart" style="width:100%;height:400px"></div>
            @else
                <h5>Nouveaux utilisateurs par mois (cette année)</h5>
                <div id="usersByPeriodChart" style="width:100%;height:400px"></div>
            @endif
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-12">
            @if($period == 'year')
                <h5>Évolution du nombre total d’utilisateurs (cette année)</h5>
            @else
                <h5>Évolution du nombre total d’utilisateurs (cette semaine)</h5>
            @endif
            <div id="userEvolutionChart" style="width:100%;height:400px"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    const countryData = [
        ['Pays', 'Utilisateurs'],
        @foreach($countries as $c)
            ["{{ $c->country ?? 'Inconnu' }}", {{ $c->count }}],
        @endforeach
    ];

    // Données dynamiques selon la période
    @if($period == 'week')
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
                $currentMonth = (int)date('n');
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
                $currentMonth = (int)date('n');
                foreach ($userEvolution as $evo) {
                    $parts = explode('-', $evo['month']);
                    if ((int)$parts[1] <= $currentMonth) {
                        $label = $parts[1] . '/' . $parts[0];
                        echo "['$label', {$evo['count']}],\n";
                    }
                }
            ?>
        ];
    @endif

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        var countryDataTable = google.visualization.arrayToDataTable(countryData);

        var countryOptions = {
            legend: { position: 'right' },
            pieHole: 0.4,
            chartArea: {width: '90%', height: '80%'},
            colors: ['#6f42c1', '#ffc107', '#28a745', '#dc3545', '#007bff', '#20c997', '#fd7e14']
        };

        var countryChart = new google.visualization.PieChart(document.getElementById('countryChart'));
        countryChart.draw(countryDataTable, countryOptions);

        var usersByPeriodTable = google.visualization.arrayToDataTable(usersByPeriodData);
        var usersByPeriodOptions = {
            legend: { position: 'none' },
            chartArea: {width: '80%', height: '70%'},
            colors: ['#6f42c1'],
            hAxis: { title: '{{ $period == "year" ? "Mois" : "Date" }}' },
            vAxis: { 
                title: 'Nouveaux utilisateurs', 
                minValue: 0,
                gridlines: { count: -1, interval: 1 },
                viewWindow: { min: 0 }
            }
        };
        var usersByPeriodChart = new google.visualization.ColumnChart(document.getElementById('usersByPeriodChart'));
        usersByPeriodChart.draw(usersByPeriodTable, usersByPeriodOptions);

        var userEvolutionTable = google.visualization.arrayToDataTable(userEvolutionData);
        var userEvolutionOptions = {
            legend: { position: 'bottom' },
            chartArea: {width: '80%', height: '70%'},
            colors: ['#007bff'],
            hAxis: { title: '{{ $period == "year" ? "Mois" : "Date" }}' },
            vAxis: { title: 'Total utilisateurs', minValue: 0 }
        };
        var userEvolutionChart = new google.visualization.LineChart(document.getElementById('userEvolutionChart'));
        userEvolutionChart.draw(userEvolutionTable, userEvolutionOptions);
    }

    window.addEventListener('resize', drawCharts);
</script>
@endpush