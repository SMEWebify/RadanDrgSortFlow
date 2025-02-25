@extends('adminlte::page')

@section('title', 'Ajouter une Machine')

@section('content_header')
    <h1>Ajouter une Machine</h1>
@stop

@section('content')
    <x-adminlte-card title="Ajouter une Machine" theme="info" icon="fas fa-lg fa-bell" collapsible removable maximizable>
        <form action="{{ route('machines.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <!-- Code -->
                    <x-adminlte-input name="code" label="Code" placeholder="Entrez le code de la machine" required />

                    <!-- Nom -->
                    <x-adminlte-input name="name" label="Nom" placeholder="Entrez le nom de la machine" required />

                    <!-- Type -->
                    <x-adminlte-select name="type" label="Type" required>
                        <option>Type 1</option>
                        <option>Type 2</option>
                        <option>Type 3</option>
                    </x-adminlte-select>

                    <!-- Capacité horaire -->
                    <x-adminlte-input name="capacity" label="Capacité Horaire" placeholder="Entrez la capacité horaire" required />
                </div>

                <div class="col-md-6">
                    <!-- Taux horaire -->
                    <x-adminlte-input name="hourly_rate" label="Taux Horaire" placeholder="Entrez le taux horaire" required />

                    <!-- Zone machine X -->
                    <x-adminlte-input name="zone_x" label="Zone Machine X" placeholder="Entrez la position X" required />

                    <!-- Zone machine Y -->
                    <x-adminlte-input name="zone_y" label="Zone Machine Y" placeholder="Entrez la position Y" required />
                    
                    <!-- Sélecteur de couleur -->
                    <x-adminlte-input name="color" label="Couleur" placeholder="Choisissez une couleur" type="color" value="#ff0000" required />

                    <!-- Image -->
                    <x-adminlte-input-file name="image" label="Image" placeholder="Choisir une image..." />
                </div>
            </div>

            <x-adminlte-button type="submit" label="Enregistrer" theme="success" />
        </form>
    </x-adminlte-card>
@stop
