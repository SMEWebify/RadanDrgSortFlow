@extends('layouts.app')

@section('title', "DRGs en cours - " . $machine->name)

@section('content')

<style>
.machine-image {
    width: 150px;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.drg-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
}

.drg-card:hover {
    transform: scale(1.05);
}
    </style>
<div class="container">
    <h1 class="text-center mt-4">📌 DRGs en cours pour la machine : {{ $machine->name }}</h1>

    @if($drgs->isEmpty())
        <p class="text-center text-muted mt-4">Aucun DRG en cours sur cette machine.</p>
    @else
        <div class="row mt-4">
            @foreach($drgs as $drg)
            <div class="col-md-4">
                <div class="card drg-card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $drg->drg_name }}</h5>
                        <p class="card-text"><strong>Matériau :</strong> {{ $drg->material }}</p>
                        <p class="card-text"><strong>Épaisseur :</strong> {{ $drg->thickness }} mm</p>
                        <p class="card-text"><strong>Quantité :</strong> {{ $drg->sheet_qty }}</p>
                        <p class="card-text"><strong>Feuilles traitées :</strong> {{ $drg->sheet_qty_done }}</p>
                        <p class="card-text"><strong>Temps total :</strong> {{ $drg->TotalTime() }} h</p>
                        <p class="card-text"><strong>Temps restant :</strong> {{ $drg->RemaningTotalTime() }} h</p>
                        <img alt="Imbrication" src="{{ asset('/images/'. $drg->drg_name .'.png') }}" width="100%">
                        <a href="{{ url('/drgs/' . $drg->id) }}" class="btn btn-info btn-sm">🔍 Détails</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ url('/machines') }}" class="btn btn-secondary">🔙 Retour aux machines</a>
    </div>
</div>
@endsection
