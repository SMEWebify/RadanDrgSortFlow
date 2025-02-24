@extends('adminlte::page')

@section('title', 'DRG Planifiées')

@section('content_header')
    <h1>DRG Planifiées</h1>
@stop

@section('content')
    <x-adminlte-card title="DRG liste planifié" theme="warning" icon="fas fa-lg fa-bell" collapsible maximizable>
        @livewire('d-r-g-planned')
    </x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
@stop