<div>
    @include('include.alert-result')
    <div class="card-body">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search fa-fw"></i></span>
            </div>
            <input type="text" class="form-control" wire:model.live="search" placeholder="Chercher une imbrication">
        </div>
    </div>
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
                <th>Temps passé logé</th>
                <th>Change statu</th>
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
                        @if($DRG->statu  != 7)
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $DRG->Advencemnt() }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $DRG->Advencemnt() }}%">
                        @else
                            <div class="progress-bar bg-red" role="progressbar" aria-valuenow="{{ $DRG->Advencemnt() }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $DRG->Advencemnt() }}%">
                        @endif
                            </div>
                    </div>
                    <small>
                        {{ round($DRG->Advencemnt(),2) }}% Complete
                    </small>
                </td>
                <td class="project-state">
                    @if($DRG->statu  == 2)<span class="badge badge-warning">Planifier</span> @endif
                    @if($DRG->statu  == 3)<span class="badge badge-info">En cours</span> @endif
                    @if($DRG->statu  == 4)<span class="badge badge-danger">A refaire</span> @endif
                    @if($DRG->statu  == 7)<span class="badge badge-danger">Stopper</span> @endif
                    
                </td>
                <td>{{ $DRG->material }}</td>
                <td>{{ $DRG->thickness }}</td>
                <td style="background-color: {{ $DRG->getMachineColor() }}">{{ $DRG->machine_name }}</td> 
                <td>{{ $DRG->sheet_qty }}</td>
                <td>
                    @if($DRG->statu  != 7)
                    <div class="btn-group btn-group-sm">
                        <button  wire:click="down({{ $DRG->id }}, {{ $DRG->unit_time }})" class="btn btn-primary"><i class="fa fa-minus"></i></button>
                    </div>
                    @endif
                    {{ $DRG->sheet_qty_done }}
                    @if($DRG->statu  != 7)
                    <div class="btn-group btn-group-sm">
                        <button wire:click="up({{ $DRG->id }}, {{ $DRG->unit_time }})" class="btn btn-secondary"><i class="fa fa-plus"></i></button>
                    </div>
                    @endif
                </td>
                <td>{{ $DRG->unit_time }} h</td>
                <td>{{ $DRG->TotalTime() }} h</td>
                <td>{{ $DRG->RemaningTotalTime() }} h</td>
                <td>{{ $DRG->real_full_time }} h 
                    <button class="btn btn-info btn-sm"  data-toggle="modal" data-target="#DRG{{ $DRG->id }}"><i class="fas fa-user-clock"></i></button>
                    <!-- Modal for edite-->
                    <div wire:ignore.self class="modal fade" id="DRG{{ $DRG->id }}" tabindex="-1" role="dialog" aria-labelledby="DRG{{ $DRG->id }}Title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h2 class="modal-title" id="DRG{{ $DRG->id }}Title">{{ $DRG->drg_name }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <h2> Ajout d'un temps de production au temps actuel : {{ $DRG->real_full_time }} h </h2>
                                        <div class="input-group">
                                            <input type="number" wire:model.live="real_full_time" name="real_full_time"  id="real_full_time" placeholder="Time ..." class="form-control">
                                            <span class="input-group-append">
                                                <button type="button" wire:click="AddTime({{ $DRG->id }})" class="btn btn-primary">Add time</button>
                                            </span>
                                        </div>
                                        @error('real_full_time') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="row">
                                        <h2> Réinitialisation du temps : </h2>
                                        <div class="input-group">
                                            <input type="number" wire:model.live="real_full_time" name="real_full_time"  id="real_full_time" placeholder="Time ..." class="form-control">
                                            <span class="input-group-append">
                                                <button type="button" wire:click="ChangeTime({{ $DRG->id }})"  class="btn btn-primary">Change time</button>
                                            </span>
                                        </div>
                                        @error('real_full_time') <span class="error">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
                </td>
                @php
                    $totalTime += $DRG->TotalTime();
                    $totalRemaningTime += $DRG->RemaningTotalTime();
                @endphp

                <td class="project-actions">
                    @if($DRG->statu  != 7)
                    <button class="btn btn-success btn-sm"  wire:click="cut({{$DRG->id}})"><i class="fas fa-folder"></i> All Cut</button>
                    <button  class="btn btn-warning btn-sm" wire:click="stop({{$DRG->id}})"><i class="fas fa-trash"></i> Stop</button>
                    @else
                    <button  class="btn btn-success btn-sm"  wire:click="run({{$DRG->id}})"><i class="fas fa-trash"></i> Relancer</button>
                    @endif
                </td>
                <td class="project-actions">
                    <a href="{{ route('drgs.edit', $DRG->id) }}" class="btn btn-warning btn-sm">Éditer</a>
                    <button class="btn btn-danger btn-sm" wire:click="delete({{$DRG->id}})"><i class="fas fa-trash"></i> Delete</button>
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
                <th>Programme Progress</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Nombre de tôle</th>
                <th>Nombre de tôle coupée</th>
                <th></th>
                <th>Temps Total</th>
                <th>Temps Restant</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <td class="project_progress">
                    @if ($totalTime > 0) 
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
                    @endif
                </td>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>{{ $totalTime }} h</th>
                <th>{{ $totalRemaningTime }} h</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
