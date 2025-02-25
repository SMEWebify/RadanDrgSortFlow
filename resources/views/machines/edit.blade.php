@extends('adminlte::page')

@section('title', 'Éditer la Machine')

@section('content_header')
    <h1>Éditer la Machine : {{ $machine->name }}</h1>
@stop

@section('content')

<x-adminlte-card title="DRG liste à planifier" theme="warning" icon="fas fa-lg fa-bell" collapsible maximizable>
    <form action="{{ route('machines.update', $machine->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <!-- Code -->
                <x-adminlte-input name="code" label="Code" value="{{ $machine->code }}" required />

                <!-- Nom -->
                <x-adminlte-input name="name" label="Nom" value="{{ $machine->name }}" required />

                <!-- Type -->
                <x-adminlte-select name="type" label="Type" required>
                    <option {{ $machine->type == 'Type 1' ? 'selected' : '' }}>Type 1</option>
                    <option {{ $machine->type == 'Type 2' ? 'selected' : '' }}>Type 2</option>
                    <option {{ $machine->type == 'Type 3' ? 'selected' : '' }}>Type 3</option>
                </x-adminlte-select>

                <!-- Capacité horaire -->
                <x-adminlte-input name="capacity" label="Capacité Horaire" value="{{ $machine->capacity }}" required />

                <!-- Sélecteur de couleur -->
                <x-adminlte-input name="color" label="Couleur" placeholder="Choisissez une couleur" type="color" value="{{ $machine->color }}" required />

            </div>

            <div class="col-md-6">
                <!-- Taux horaire -->
                <x-adminlte-input name="hourly_rate" label="Taux Horaire" value="{{ $machine->hourly_rate }}" required />

                <!-- Zone machine X -->
                <x-adminlte-input name="zone_x" label="Zone Machine X" value="{{ $machine->zone_x }}" required />

                <!-- Zone machine Y -->
                <x-adminlte-input name="zone_y" label="Zone Machine Y" value="{{ $machine->zone_y }}" required />

                <!-- Image -->
                <x-adminlte-input-file name="image" label="Image" placeholder="Choisir une nouvelle image..." />
                @if ($machine->image)
                <img src="{{ asset('images/machines/' . $machine->image) }}" alt="Image de la machine" class="img-fluid">
                @endif
            </div>
        </div>

        <x-adminlte-button type="submit" label="Mettre à jour" theme="success" />
    </form>
</x-adminlte-card>
@stop
