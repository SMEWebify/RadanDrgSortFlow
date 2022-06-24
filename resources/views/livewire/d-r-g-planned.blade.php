<div>
    @include('include.alert-result')
    <table class="table table-striped projects">
        <thead>
            <tr>
                <th>#</th>
                <th>DRG nome</th>
                <th>Image</th>
                <th>Programme Progress</th>
                <th>Status</th>
                <th>Matière</th>
                <th>Epaisseur</th>
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
                        {{ $DRG->sheet_qty_done/$DRG->sheet_qty*100 }}% Complete
                    </small>
                </td>
                <td class="project-state">
                    @if($DRG->statu  == 2)<span class="badge badge-warning">Planifier</span> @endif
                    @if($DRG->statu  == 3)<span class="badge badge-info">En cours</span> @endif
                    @if($DRG->statu  == 4)<span class="badge badge-danger">A refaire</span> @endif
                </td>
                <td>{{ $DRG->material }}</td>
                <td>{{ $DRG->thickness }}</td>
                <td>{{ $DRG->sheet_qty }}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="#" wire:click="up({{ $DRG->id }})" class="btn btn-secondary"><i class="fa fa-plus"></i></a>
                    </div>
                    {{ $DRG->sheet_qty_done }}
                    <div class="btn-group btn-group-sm">
                        <a href="#" wire:click="down({{ $DRG->id }})" class="btn btn-primary"><i class="fa fa-minus"></i></a>
                    </div>
                </td>
                <td>{{ $DRG->unit_time }} h</td>
                <td>{{ $DRG->TotalTime() }} h</td>
                <td>{{ $DRG->RemaningTotalTime() }} h</td>

                @php
                    $totalTime += $DRG->TotalTime();
                    $totalRemaningTime += $DRG->RemaningTotalTime();
                @endphp

                <td class="project-actions">
                    <a class="btn btn-success btn-sm" href="#">
                    <i class="fas fa-folder"></i>
                    All Cut
                    </a>
                    <a class="btn btn-info btn-sm" href="#">
                    <i class="fas fa-pencil-alt"></i>
                    Edit
                    </a>
                    <a class="btn btn-danger btn-sm" href="#" wire:click="delete({{$DRG->id}})">
                        <i class="fas fa-trash"></i>Delete
                    </a>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="13">Aucun programme plannfiée<td>
                <tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <td class="project_progress">
                    <div class="progress progress-sm">
                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ 100-($totalRemaningTime/$totalTime*100) }}" 
                                                                            aria-valuemin="0" 
                                                                            aria-valuemax="100" 
                                                                            style="width: {{ 100-($totalRemaningTime/$totalTime*100) }}%">
                    </div>
                    
                    </div>
                    <small>
                        {{ round(100-($totalRemaningTime/$totalTime*100),2) }}% Complete
                    </small>
                </td>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total :</th>
                <th>{{ $totalTime }} h</th>
                <th>{{ $totalRemaningTime }} h</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
