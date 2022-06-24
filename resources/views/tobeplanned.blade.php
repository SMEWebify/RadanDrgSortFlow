@extends('adminlte::page')

@section('title', 'DRG à plannifier')

@section('content_header')
    <h1>DRG à plannifier</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">DRG liste à planifier</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @livewire('d-r-g-to-be-planned')
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
@stop