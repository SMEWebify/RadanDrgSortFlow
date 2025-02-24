@extends('adminlte::page')

@section('title', 'Affecter programme')

@section('content_header')
    <h1>Affecter programme aux machines</h1>
@stop

@section('content')
    <div id="app">
        <kanban :drgs="{{ $drgsWithoutMachine }}" :machines="{{ $machines }}"></kanban>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="/js/app.js"></script>
@stop