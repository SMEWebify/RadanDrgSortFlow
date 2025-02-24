@extends('adminlte::page')

@section('title', 'Détails de la Machine')

@section('content_header')
    <h1>Détails de la Machine : {{ $machine->name }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Informations de la Machine" theme="info" icon="fas fa-info-circle">
                <p><strong>Code : </strong>{{ $machine->code }}</p>
                <p><strong>Nom : </strong>{{ $machine->name }}</p>
                <p><strong>Type : </strong>{{ $machine->type }}</p>
                <p><strong>Capacité Horaire : </strong>{{ $machine->capacity }}</p>
                <p><strong>Taux Horaire : </strong>{{ $machine->hourly_rate }}</p>
                <p><strong>Zone X : </strong>{{ $machine->zone_x }}</p>
                <p><strong>Zone Y : </strong>{{ $machine->zone_y }}</p>
                <a href="{{ route('machines.index') }}" class="btn btn-secondary">Retour</a>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="Image" theme="info" icon="fas fa-image">
                @if ($machine->image)
                    <img src="{{ asset('storage/' . $machine->image) }}" alt="Image de la machine" class="img-fluid">
                @else
                    <p>Aucune image disponible.</p>
                @endif
            </x-adminlte-card>
        </div>
    </div>
@stop
