@extends('adminlte::page')

@section('title', 'Modifier le DRG')

@section('content_header')
    <h1>Modifier le DRG : {{ $drg->name }}</h1>
@stop

@section('content')
    <x-adminlte-card title="DRG liste à planifier" theme="warning" icon="fas fa-lg fa-bell" collapsible maximizable>
        <form action="{{ route('drgs.update', $drg->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nom du DRG</label>
                <input type="text" name="drg_name" class="form-control" value="{{ $drg->drg_name }}" required>
            </div>
            <div class="form-group">
                <label>Chemin du fichier</label>
                <input type="text" name="file_path" class="form-control" value="{{ $drg->file_path }}" required>
            </div>
            <div class="form-group">
                <label>Matériau</label>
                <input type="text" name="material" class="form-control" value="{{ $drg->material }}" required>
            </div>
            <div class="form-group">
                <label>Épaisseur</label>
                <input type="number" name="thickness" class="form-control" value="{{ $drg->thickness }}" required>
            </div>
            <div class="form-group">
                <label>Quantité de feuilles</label>
                <input type="number" name="sheet_qty" class="form-control" value="{{ $drg->sheet_qty }}" required>
            </div>
            <div class="form-group">
                <label>Temps unitaire</label>
                <input type="number" name="unit_time" class="form-control" value="{{ $drg->unit_time }}" required>
            </div>
            <div class="form-group">
                <label>Machine</label>
                <select name="machine_id" class="form-control">
                    <option value="">Aucune machine</option>
                    @foreach($machines as $machine)
                    <option value="{{ $machine->id }}" {{ $drg->machine_id == $machine->id ? 'selected' : '' }}>
                        {{ $machine->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Commentaire</label>
                <textarea name="comment" class="form-control">{{ $drg->comment }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </x-adminlte-card>
@endsection
