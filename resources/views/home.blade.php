@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <x-adminlte-small-box title="{{ $data['to_be_planned_count'] }}" text="A plannifier" icon="fas fa-download text-white"
        theme="info" url="{{ route('tobeplanned') }}" url-text="View details"/>
    </div>
    <div class="col-lg-3 col-6">
        <x-adminlte-small-box title="{{ $data['planned_count'] }}" text="Plannifié" icon="fas fa-eye text-dark"
        theme="warning" url="{{ route('planned') }}" url-text="View details"/>
    </div>
    <div class="col-lg-3 col-6">
        <x-adminlte-small-box title="{{ $data['done_count'] }}" text="Coupé" icon="fas fa-medal text-white"
        theme="success" url="{{ route('cut') }}" url-text="View details"/>
    </div>
    <div class="col-lg-3 col-6">
        <x-adminlte-small-box title="{{ $data['stop_count'] }}" text="Stopé" icon="fas fa-eye text-dark"
        theme="danger" url="{{ route('planned') }}" url-text="View details"/>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ion ion-clipboard mr-1"></i>Total des heures par mois</h3>
            </div>
            <div class="card-body">
                <div id="columnchart_values" style=" height: 300px;"></div>
            </div>
        </div>

        @livewire('chat')
    </div>

    <div class="col-lg-6 col-6">
        @livewire('to-do-list')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ion ion-clipboard mr-1"></i>Total des heures par matière :</h3>
            </div>
            <div class="card-body">
                <div id="piechart" style=" height: 400px;"></div>
            </div>
        </div>
    </div>
</div>
    
@stop

@section('css')
@stop

@section('js')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
    
        function drawChart() {
    
            var data = google.visualization.arrayToDataTable([
            ['Material', 'Count'],
            @foreach ($data['materialDataRate'] as $item)
                ["{{$item->material}}", {{ $item->DrgTotalTime }}],
            @endforeach
            ]);
    
            var options = {
            };
    
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    
            chart.draw(data, options);
        }
    </script>

    <script type="text/javascript">
        google.charts.load("current", {packages:['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Mois", "Total heure", { role: "style" } ],
                ["Janvier", 8.94, 'green'],
                ["Février", 10.49, ''],
                ["Mars", 39.30, 'green'],
                ["Avril", 21.45, ''],
                ["Mai", 21.45, 'green'],
                ["Juin", 21.45, ''],
                ["Juillet", 21.45, 'green'],
                ["Août", 21.45, ''],
                ["Septembre", 21.45, 'green'],
                ["Octobre", 51.45, ''],
                ["Novembre", 51.45, 'green'],
                ["Decembre", 41.45, '']
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                            { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                            2]);

            var options = {
                bar: {groupWidth: "95%"},
                legend: { position: "none" },
            };
            var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
            chart.draw(view, options);
        }
    </script>
@stop