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
                <th>Status</th>
                <th>
                    <a class="btn btn-secondary" wire:click.prevent="sortBy('material')" role="button" href="#">Matière @include('include.sort-icon', ['field' => 'material'])</a>
                </th>
                <th>
                    <a class="btn btn-secondary" wire:click.prevent="sortBy('thickness')" role="button" href="#">Epaisseur @include('include.sort-icon', ['field' => 'thickness'])</a>
                </th>
                <th>Nombre de tôle</th>
                <th>Temps unitaire</th>
                <th>Temps Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTime = 0;
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
                <td class="project-state">
                    @if($DRG->statu  == 1)<span class="badge badge-warning">A planifier</span> @endif
                </td>
                <td>{{ $DRG->material }}</td>
                <td>{{ $DRG->thickness }}</td>
                <td>{{ $DRG->sheet_qty }}</td>
                <td>{{ $DRG->unit_time }} h</td>
                <td>{{ $DRG->TotalTime() }} h</td>

                @php
                    $totalTime += $DRG->TotalTime();
                @endphp

                <td class="project-actions">
                    <button class="btn btn-warning btn-sm"  wire:click="run({{$DRG->id}})"><i class="fas fa-folder"></i>Planifier</button>
                    <a href="{{ route('drgs.edit', $DRG->id) }}" class="btn btn-warning btn-sm">Éditer</a>
                    <button class="btn btn-danger btn-sm"  wire:click="delete({{$DRG->id}})"><i class="fas fa-trash"></i>Delete</button>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="13">Aucun programme à plannfier<td>
                <tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total :</th>
                <th>@if ($totalTime > 0) {{ round($totalTime,2) }} h @endif</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
