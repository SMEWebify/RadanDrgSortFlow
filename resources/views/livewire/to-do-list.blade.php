<div class="card" >
    <div class="card-header">
        <h3 class="card-title"><i class="ion ion-clipboard mr-1"></i>To Do List</h3>
        <div class="card-tools">
            {{ $ToDolist->links() }}
        </div>
    </div>
        
    <div class="card-body">
        <ul class="todo-list" data-widget="todo-list">
            @forelse ($ToDolist as $ToDo)
            <li @if($ToDo->statu == 2) class="done" @endif>
                @if($ToDo->statu == 1)  
                    <div class="icheck-primary d-inline ml-2">
                        <input type="checkbox" wire:click="Done({{ $ToDo->id }})" value="" name="todo1" id="todoCheck1">
                        <label for="todoCheck1"></label>
                    </div>
                @endif
                <span class="text">{{ $ToDo->label }}</span>
                <small class="badge badge-info"><i class="far fa-clock"></i> {{ $ToDo->GetPrettyCreatedAttribute() }}</small>
                
                <div class="tools">
                    <a href="#" wire:click="delete({{ $ToDo->id }})" ><i class="fas fa-trash"></i></a>
                </div>
            </li>
            @empty
            <li>
                <span class="text">Rien à faire</span>
            </li>
            @endforelse
        </ul>
    </div>

    <div class="card-footer clearfix">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-list"></i></span>
            </div>
            <input type="text" class="form-control" placeholder="To Do" wire:model="label" name="label"  id="label">
            <button type="button"  wire:click="storeTodo()" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add item</button>
        </div>
        @error('label') <span class="text-danger">{{ $message }}<br/></span>@enderror
    </div>
</div>

