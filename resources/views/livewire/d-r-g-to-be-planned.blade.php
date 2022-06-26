<div>
    @include('include.alert-result')
    <table class="table table-striped projects">
        <thead>
            <tr>
                <th>#</th>
                <th>DRG nome</th>
                <th>Image</th>
                <th>Status</th>
                <th>Matière</th>
                <th>Epaisseur</th>
                <th>Nombre de tôle</th>
                <th>Temps unitaire</th>
                <th>Temps Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTime = 0.0000001;
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
                    <a class="btn btn-warning btn-sm" href="#" wire:click="run({{$DRG->id}})"><i class="fas fa-folder"></i>Planifier</a>
                    <a class="btn btn-info btn-sm" href="#"><i class="fas fa-pencil-alt"></i>Edit</a>
                    <a class="btn btn-danger btn-sm" href="#" wire:click="delete({{$DRG->id}})"><i class="fas fa-trash"></i>Delete</a>
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
                <th>{{ round($totalTime,2) }} h</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
