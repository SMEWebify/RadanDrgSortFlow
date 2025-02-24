@extends('adminlte::page')

@section('title', 'DRG supprimés')

@section('content_header')
    <h1>DRG supprimés</h1>
@stop

@section('content')
    <x-adminlte-card title="Liste des DRG supprimés" theme="danger" icon="fas fa-lg fa-bell" collapsible maximizable>
        @livewire('d-r-g-delete')
    </x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
@stop