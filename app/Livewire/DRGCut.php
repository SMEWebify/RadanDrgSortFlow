<?php

namespace App\Livewire;

use App\Models\Drg;
use Livewire\Component;
use Livewire\WithPagination;

class DRGCut extends Component
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
                                        ->where('statu', 5)->get();

        return view('livewire.d-r-g-cut', [
            'DRGList' => $DRGList,
        ]);
    }

    public function delete($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>6]);
    }

    public function restorToPlanned($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>2]);
        session()->flash('success','ligne restaurée planifiée');
    }

    public function restorToBePlanned($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>1]);
        session()->flash('success','ligne restaurée à planifiée');
    }
}
