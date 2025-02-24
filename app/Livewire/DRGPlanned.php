<?php

namespace App\Livewire;

use App\Models\Drg;
use Livewire\Component;
use Livewire\WithPagination;

class DRGPlanned extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'created_at'; // default sorting field
    public $sortAsc = false; // default sort direction
    
    public $DRGList;
    public $real_full_time;

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
                                        ->where('drg_name','like', '%'.$this->search.'%')
                                        ->where(function($q){
                                                $q->where('statu', 2)
                                                ->Orwhere('statu', 3)
                                                ->Orwhere('statu', 4)
                                                ->Orwhere('statu', 7);
                                            })
                                        ->get();
                                        
        return view('livewire.d-r-g-planned', [
            'DRGList' => $DRGList,
        ]);
    }

    public function up($idStatu, $unitTime){
        // Update line
        Drg::find($idStatu)->increment('sheet_qty_done',1);
        Drg::find($idStatu)->increment('real_full_time', $unitTime);
        Drg::find($idStatu)->update(['statu'=>3]);
    }

    public function down($idStatu, $unitTime){
        // Update line
        Drg::find($idStatu)->decrement('sheet_qty_done',1);
        Drg::find($idStatu)->decrement('real_full_time', $unitTime);
    }

    
    public function stop($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>7]);
    }

    public function run($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>3]);
    }

    public function delete($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>6]);
    }

    public function cut($idStatu){
        // Update line
        Drg::find($idStatu)->update(['statu'=>5]);
        session()->flash('warning','Ligne finie');
    }

    public function AddTime($id){
        Drg::find($id)->increment('real_full_time', $this->real_full_time);
    }
    
    public function ChangeTime($id){
        Drg::find($id)->update(['real_full_time'=>$this->real_full_time]);
    }
}
