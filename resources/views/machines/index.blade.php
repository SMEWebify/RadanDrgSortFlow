@extends('adminlte::page')

@section('title', 'Liste des Machines')

@section('content_header')
    <h1>Liste des Machines</h1>
@stop

@section('content')
    <x-adminlte-card theme="lime" theme-mode="outline">
        <a href="{{ route('machines.create') }}" class="btn btn-success">Ajouter une Machine</a>
    </x-adminlte-card>

    <x-adminlte-card title="Liste des Machines" theme="info" icon="fas fa-lg fa-bell" collapsible removable maximizable>
        <table id="machinesTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Capacité Horaire</th>
                    <th>Taux Horaire</th>
                    <th>Couleur</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($machines as $machine)
                    <tr>
                        <td>{{ $machine->code }}</td>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $machine->type }}</td>
                        <td>{{ $machine->capacity }}</td>
                        <td>{{ $machine->hourly_rate }}</td>
                        <td style="background-color: {{ $machine->color }}">{{ $machine->color }}</td>
                        <td>
                            <span class="badge {{ $machine->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $machine->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('machines.show', $machine->id) }}" class="btn btn-primary">Voir</a>
                            <a href="{{ route('machines.edit', $machine->id) }}" class="btn btn-warning">Éditer</a>
                            <form action="{{ route('machines.destroy', $machine->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
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
        $('#machinesTable').DataTable();
    });
</script>
@stop
