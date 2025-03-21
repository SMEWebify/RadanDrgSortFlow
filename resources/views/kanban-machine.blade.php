@extends('adminlte::page')

@section('title', 'Affecter programme')

@section('content_header')
    <h1>Affecter programme aux machines</h1>
@stop


@section('content')
<div id="app">
    <kanban-machine :machines='@json($machines)' :unassigned-drgs='@json($unassignedDrgs)'></kanban-machine>
</div>

@stop

@section('css')
@stop

@section('js')
<script src="{{ mix('/js/app.js') }}"></script>
@stop