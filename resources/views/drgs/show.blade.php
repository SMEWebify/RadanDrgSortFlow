@extends('adminlte::page')

@section('title', 'Détails du DRG')

@section('content_header')
    <h1>Détails du DRG : {{ $drg->drg_name }}</h1>
@stop

@section('content')

    
    <div class="row">
        <div class="col-md-6">
            <x-adminlte-card title="Informations du DRG" theme="info" icon="fas fa-info-circle">
                    <h5 class="card-title">{{ $drg->drg_name }}</h5>
                    <p><strong>Machine :</strong> {{ optional($drg->machine)->name ?? 'Aucune machine' }}</p>
                    <p><strong>Matériau :</strong> {{ $drg->material }}</p>
                    <p><strong>Épaisseur :</strong> {{ $drg->thickness }} mm</p>
                    <p><strong>Quantité de feuilles :</strong> {{ $drg->sheet_qty }}</p>
                    <p><strong>Temps unitaire :</strong> {{ $drg->unit_time }} heures</p>
                    <p><strong>Commentaire :</strong> {{ $drg->comment }}</p>
                    <a href="{{ route('drgs.index') }}" class="btn btn-secondary">Retour</a>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            <x-adminlte-card title="Image" theme="info" icon="fas fa-image">
                @if ($drg->drg_name)
                    <img alt="Imbrication" src="{{ asset('/images/'. $drg->drg_name .'.png') }}" width="100%">
                @else
                    <p>Aucune image disponible.</p>
                @endif
            </x-adminlte-card>
        </div>
    </div>
@endsection
