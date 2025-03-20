@extends('adminlte::page')

@section('title', 'Changer statut')

@section('content_header')
    <h1>Changer statut des programmes</h1>
@stop


@section('content')
<div id="app">
    <kanban-statut :drgs='@json($drgs)'></kanban-statut>
</div>

@stop

@section('css')
@stop

@section('js')
<script src="{{ mix('/js/app.js') }}"></script>
@stop