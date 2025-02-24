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
        <x-adminlte-card title="Total des heures par mois" theme="info" icon="fas fa-lg fa-bell" collapsible removable maximizable>
            <div id="columnchart_values" style=" height: 300px;"></div>
        </x-adminlte-card>

        @livewire('chat')
    </div>

    <div class="col-lg-6 col-6">
        @livewire('to-do-list')
        
            <x-adminlte-card title="Total des heures par matière :" theme="orange" icon="fas fa-lg fa-bell" collapsible removable maximizable>
                <div id="piechart" style=" height: 400px;"></div>
            </x-adminlte-card>
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
                ["Janvier", {{ $data['janvier_count'][0]->total_time }}, 'green'],
                ["Février", {{ $data['fevrier_count'][0]->total_time  }}, ''],
                ["Mars", {{ $data['mars_count'][0]->total_time  }}, 'green'],
                ["Avril", {{ $data['avril_count'][0]->total_time  }}, ''],
                ["Mai", {{ $data['mai_count'][0]->total_time  }}, 'green'],
                ["Juin", {{ $data['juin_count'][0]->total_time  }}, ''],
                ["Juillet", {{ $data['juillet_count'][0]->total_time  }}, 'green'],
                ["Août", {{ $data['aout_count'][0]->total_time  }}, ''],
                ["Septembre", {{ $data['septembre_count'][0]->total_time  }}, 'green'],
                ["Octobre", {{ $data['octobre_count'][0]->total_time  }}, ''],
                ["Novembre", {{ $data['novembre_count'][0]->total_time  }}, 'green'],
                ["Decembre", {{ $data['decembre_count'][0]->total_time  }}, '']
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