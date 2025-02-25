<div>
    @include('include.alert-result')
    <table class="table table-striped projects">
        <thead>
            <tr>
                <th>#</th>
                <th>DRG nom </th>
                <th>Image</th>
                <th>Programme Progress</th>
                <th>Status</th>
                <th>
                    <a class="btn btn-secondary" wire:click.prevent="sortBy('material')" role="button" href="#">Matière @include('include.sort-icon', ['field' => 'material'])</a>
                </th>
                <th>
                    <a class="btn btn-secondary" wire:click.prevent="sortBy('thickness')" role="button" href="#">Epaisseur @include('include.sort-icon', ['field' => 'thickness'])</a>
                </th>
                <th>Machine</th>
                <th>Nombre de tôle</th>
                <th>Nombre de tôle coupée</th>
                <th>Temps unitaire</th>
                <th>Temps Total</th>
                <th>Temps Restant</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTime = 0;
                $totalRemaningTime = 0;
            @endphp

            @forelse ($DRGList as $DRG)
            <tr>
                <td>#</td>
                <td>
                    <a>{{ $DRG->drg_name }}</a><br />
                    <small>{{ $DRG->GetPrettyCreatedAttribute() }}</small>
                </td>
                <td>
                    <img alt="Imbrication" src="{{ asset('/images/'. $DRG->drg_name .'.png') }}">
                </td>
                <td class="project_progress">
                    <div class="progress progress-sm">
                        <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $DRG->sheet_qty_done/$DRG->sheet_qty*100 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $DRG->sheet_qty_done/$DRG->sheet_qty*100 }}%">
                        </div>
                    </div>
                    <small>
                        {{ round($DRG->sheet_qty_done/$DRG->sheet_qty*100,2) }}% Complete
                    </small>
                </td>
                <td class="project-state">
                    @if($DRG->statu  == 6)<span class="badge badge-danger">Supprimer</span> @endif
                </td>
                <td>{{ $DRG->material }}</td>
                <td>{{ $DRG->thickness }}</td>
                <td style="background-color: {{ $DRG->getMachineColor() }}">{{ $DRG->machine_name }}</td> 
                <td>{{ $DRG->sheet_qty }}</td>
                <td>{{ $DRG->sheet_qty_done }}</td>
                <td>{{ $DRG->unit_time }} h</td>
                <td>{{ $DRG->TotalTime() }} h</td>
                <td>{{ $DRG->RemaningTotalTime() }} h</td>

                @php
                    $totalTime += $DRG->TotalTime();
                    $totalRemaningTime += $DRG->RemaningTotalTime();
                @endphp

                <td class="project-actions">
                    <button class="btn btn-success btn-sm"  wire:click="restorToCut({{$DRG->id}})"><i class="fas fa-folder"></i>Restaurer coupée</button>
                    <button class="btn btn-warning btn-sm"  wire:click="restorToPlanned({{$DRG->id}})"><i class="fas fa-folder"></i>Restaurer planifiée</button>
                    <button class="btn btn-info btn-sm"  wire:click="restorToBePlanned({{$DRG->id}})"><i class="fas fa-folder"></i>Restaurer à planifiée</button>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="13">Aucun programme dans la corbeille<td>
                <tr>
            @endforelse
        </tbody>
    </table>
</div>
