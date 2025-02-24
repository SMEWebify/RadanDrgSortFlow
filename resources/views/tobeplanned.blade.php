@extends('adminlte::page')

@section('title', 'DRG à plannifier')

@section('content_header')
    <h1>DRG à plannifier</h1>
@stop

@section('content')
    <x-adminlte-card title="DRG liste à planifier" theme="info" icon="fas fa-lg fa-bell" collapsible maximizable>
        @livewire('d-r-g-to-be-planned')
    </x-adminlte-card>
@stop

@section('css')
@stop

@section('js')
@stop