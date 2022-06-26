<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Chat As ModelChat;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $sortField = 'id'; // default sorting field
    public $sortAsc = true; // default sort direction
    public $label;

    private $ChatMessages;

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
        $ChatMessages = $this->ChatMessages = ModelChat::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                                    ->get();

        return view('livewire.chat', [
            'ChatMessages' => $ChatMessages,
        ]);
    }

    public function storeMessage(){
        $this->validate();

        // Create Line
        $Todo = ModelChat::create([
            'label'=> $this->label,  
            'user_id'=> Auth::id(), 
        ]);
    }
    
}
