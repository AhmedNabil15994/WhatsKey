<div class="flex-row-fluid ml-lg-8" id="kt_chat_content">
    <div class="card card-custom" style="height: 100%; background: url({{asset('assets/tenant/images/bg-chat.png')}});">
        <div class="card-header px-1 py-1" style="display: block;min-height: 45px;background:{{$selected ? '#FFF':'transparent'}}"> 
        @if($selected)
        @php
            $chat['name'] = mb_convert_encoding($chat['name'], 'UTF-8', 'UTF-8');
        @endphp
        <livewire:chat-actions  :name="$chat['name']" :wire:key="'actions'.$chat['id']" />
        @endif
        </div>
        <div class="card-body" style="position:relative;padding: 0 1.5rem; {{$selected ? 'background: rgba(0,0,0,.25)' : ''}};">
            <div class="scroll scroll-pull scroll-pulls" data-mobile-height="350">
                <div class="messages" id="messages">
                    @if($selected)
                    @foreach(array_reverse($messages) as $oneMessage)
                    @php 
                        $oneMessage = (array) $oneMessage; 
                        // dd($messages);
                        $oneMessage['body'] = mb_convert_encoding($oneMessage['body'], 'UTF-8', 'UTF-8');
                        $oneMessage['metadata'] = (array) $oneMessage['metadata']; 
                        $id = array_reverse(explode('_',$oneMessage['id']))[0];
                    @endphp
                    <livewire:message :wire:key="time().$id" :msg="$oneMessage" :chatName="$chat['name']"/>               
                    @endforeach
                    @endif
                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="kt_scrollDown" class="scrollDown text-right float-right hidden">
                <span class="fa-icon fa-icon-xl">
                    <i class="la la-angle-double-down text-white"></i>
                </span>
            </div>
        </div>
        <div class="card-footer px-3 py-3 align-items-center" style="background:{{$selected ? '#FFF':'transparent'}}">
        @if($selected)
        <livewire:send-msg :selected="$selected" :wire:key="'send'.$chat['id']"/>
        @endif
        </div>
    </div>
</div>


