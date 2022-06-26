<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ToDoList As ModelToDoList;

class ToDoList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'created_at'; // default sorting field
    public $sortAsc = false; // default sort direction
    public $label;

    private $ToDolist;

    // Validation Rules
    protected $rules = [
        'label'=>'required',
    ];

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
        $ToDolist = $this->ToDolist = ModelToDoList::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                                    ->where('statu', 1)
                                                    ->Orwhere('statu', 2)
                                                    ->paginate(6);

        return view('livewire.to-do-list', [
            'ToDolist' => $ToDolist,
        ]);
    }

    public function storeTodo(){
        $this->validate();

        // Create Line
        $Todo = ModelToDoList::create([
            'label'=> $this->label,    
        ]);
    }

    public function Done($id){
        // Update line
        ModelToDoList::find($id)->update(['statu'=>2]);
    }

    public function delete($id){
        // Update line
        ModelToDoList::find($id)->update(['statu'=>3]);
    }

}
