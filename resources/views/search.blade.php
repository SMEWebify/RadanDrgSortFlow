@extends('adminlte::page')

@section('title', 'Search results')

@section('content_header')
    <h1>Search results</h1>
@stop

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th>Statu</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @php $Table =''  @endphp
                    @forelse ($results as $result)
                    @if( $result->getTable() != $Table) 
                    <tr>
                        <th colspan="4" class="bg-secondary disabled color-palette">{{ $result->getTable() }}</th>
                        @php $Table = $result->getTable()  @endphp
                    </tr>
                    @endif
                    <tr>
                        <td>{{ $result->drg_name }}</td>
                        <td>
                            @if($result->statu  == 1)<span class="badge badge-warning">A Planifier</span> @endif
                            @if($result->statu  == 2)<span class="badge badge-warning">Planifié</span> @endif
                            @if($result->statu  == 3)<span class="badge badge-info">En cours</span> @endif
                            @if($result->statu  == 4)<span class="badge badge-danger">A refaire</span> @endif
                            @if($result->statu  == 5)<span class="badge badge-danger">Terminé</span> @endif
                            @if($result->statu  == 6)<span class="badge badge-danger">Supprimé</span> @endif
                            @if($result->statu  == 7)<span class="badge badge-danger">Stopper</span> @endif
                            
                        </td>
                        <td>{{ $result->GetPrettyCreatedAttribute() }}</td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="2">
                                Aucun résulta
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>Label</th>
                        <th>Statu</th>
                        <th>Created At</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- /.card -->
@stop

@section('css')
@stop

@section('js')
@stop