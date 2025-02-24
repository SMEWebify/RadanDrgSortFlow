@extends('adminlte::page')

@section('title', 'Ajouter un DRG')

@section('content_header')
    <h1>Ajouter un DRG</h1>
@stop

@section('content')
    <x-adminlte-card title="Ajouter un DRG" theme="info" icon="fas fa-lg fa-bell" collapsible removable maximizable>
        <form action="{{ route('drgs.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nom du DRG</label>
                <input type="text" name="drg_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Chemin du fichier</label>
                <input type="text" name="file_path" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Matériau</label>
                <input type="text" name="material" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Épaisseur</label>
                <input type="number" name="thickness" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Quantité de feuilles</label>
                <input type="number" name="sheet_qty" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Temps unitaire</label>
                <input type="number" name="unit_time" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Machine</label>
                <select name="machine_id" class="form-control">
                    <option value="">Aucune machine</option>
                    @foreach($machines as $machine)
                    <option value="{{ $machine->id }}">{{ $machine->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Commentaire</label>
                <textarea name="comment" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </x-adminlte-card>
@endsection
