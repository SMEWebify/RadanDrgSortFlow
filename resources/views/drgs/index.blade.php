@extends('adminlte::page')

@section('title', 'Liste des Machines')

@section('content_header')
    <h1>Liste des DRGs</h1>
@stop

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <a href="{{ route('drgs.create') }}" class="btn btn-success mb-3">Ajouter un DRG</a>
    </x-adminlte-card>
    <x-adminlte-card title="Liste des DRGs" theme="info" icon="fas fa-lg fa-bell" collapsible removable maximizable>

        <table id="drgsTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Matériau</th>
                    <th>Épaisseur</th>
                    <th>Machine</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($drgs as $drg)
                    <tr>
                        <td>{{ $drg->id }}</td>
                        <td>{{ $drg->drg_name }}</td>
                        <td>{{ $drg->material }}</td>
                        <td>{{ $drg->thickness }}</td>
                        <td>{{ $drg->machine->name ?? 'Non assigné' }}</td>
                        @php
                            $statuLabel = $drg->getStatuLabel();
                        @endphp
                        <td><span class="{{ $statuLabel['class'] }}">{{ $statuLabel['text'] }}</span></td>
                        <td>
                            <a href="{{ route('drgs.show', $drg->id) }}" class="btn btn-info btn-sm">Voir</a>
                            <a href="{{ route('drgs.edit', $drg->id) }}" class="btn btn-warning btn-sm">Éditer</a>
                            <form action="{{ route('drgs.destroy', $drg->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-adminlte-card>
@stop

@section('js')
    <script>
        $(document).ready(function () {
            $('#drgsTable').DataTable();
        });
    </script>
@stop
