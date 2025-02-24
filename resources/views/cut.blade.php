@extends('adminlte::page')

@section('title', 'DRG coupés')

@section('content_header')
    <h1>DRG coupés</h1>
@stop

@section('content')
    <x-adminlte-card title="Liste des DRG coupés" theme="success" icon="fas fa-lg fa-bell" collapsible maximizable>
        @livewire('d-r-g-cut')
    </x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
@stop