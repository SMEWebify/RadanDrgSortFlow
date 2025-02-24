<div>
    <x-adminlte-card title="Direct Chat" theme="warning" icon="fas fa-lg fa-bell" collapsible maximizable>
        <div class="direct-chat-messages">
            @php 
                $styleChatMsg="";
                $styleName="left";
                $styletimestamp="right";
                $lastid=0;
                $i = 1;
            @endphp

            @forelse ($ChatMessages as $ChatMessage)
                @php
                    if($ChatMessage->user_id != $lastid){
                        $i++;
                        $lastid = $ChatMessage->user_id;
                    }

                    if($i%2 == 1){
                        $styleChatMsg="right";
                        $styleName="right";
                        $styletimestamp="left";
                    }
                    else{
                        $styleChatMsg="left";
                        $styleName="left";
                        $styletimestamp="right";
                    }
                @endphp
            <div class="direct-chat-msg {{ $styleChatMsg }}">
                <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-{{ $styleName }}">{{ $ChatMessage->User->name }}</span>
                    <span class="direct-chat-timestamp float-{{ $styletimestamp }}">{{ $ChatMessage->GetPrettyCreatedAttribute() }}</span>
                </div>
            
                <!--<img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">-->
            
                <div class="direct-chat-text">
                    {{ $ChatMessage->label }}     
                </div>
            </div>

            @empty
                <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-left">system</span>
                    <span class="direct-chat-timestamp float-right">Depuis tout le temps</span>
                </div>
            
                <!--<img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">-->
            
                <div class="direct-chat-text">
                    Aucun message.
                </div>
            </div>
            @endforelse
        </div> 
    <div class="card-footer">
        <div class="input-group">
            <input type="text" wire:model.live="label" name="label"  id="label" placeholder="Message ..." class="form-control">
            <span class="input-group-append">
                <button type="button" wire:click="storeMessage()" class="btn btn-primary">Envoyer</button>
            </span>
        </div>
        @error('label') <span class="text-danger">{{ $message }}<br/></span>@enderror
    </div>
</x-adminlte-card>
</div>