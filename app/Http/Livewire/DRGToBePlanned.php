<?php

namespace App\Http\Livewire;

use App\Models\Drg;
use Livewire\Component;
use Livewire\WithPagination;

class DRGToBePlanned extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'created_at'; // default sorting field
    public $sortAsc = false; // default sort direction
    
    public $DRGList;

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc; 
        } else {
            $this->sortAsc = true; 
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $DRGList = $this->DRGList = Drg::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                        ->where('statu', 1)
                                        ->where('drg_name','like', '%'.$this->search.'%')
                                        ->get();

        return view('livewire.d-r-g-to-be-planned', [
            'DRGList' => $DRGList,
        ]);
    }
    
    public function run($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>2]);
        session()->flash('success','Ligne ajoutée à la liste des DRG à couper.');
    }

    public function delete($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>6]);
        session()->flash('success','Ligne ajoutée à la corbeille');
    }
}
