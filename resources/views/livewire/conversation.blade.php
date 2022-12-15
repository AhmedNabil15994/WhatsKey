<div class="flex-row-fluid ml-lg-8" id="kt_chat_content">
    <div class="card card-custom">
        @if($selected)
        <livewire:chat-actions  :name="$chat['name']" :wire:key="$chat['id'].$chat['name']" />
        @endif

        <div class="card-body">
            <div class="scroll scroll-pull scroll-pulls" data-mobile-height="350">
                <div class="messages" id="messages">
                    @if($selected)
                    @foreach(array_reverse($messages) as $oneMessage)
                    @php $oneMessage = (array) $oneMessage; @endphp
                    <div class="d-flex flex-column mb-5 align-items-{{$oneMessage['fromMe'] == 1 ? 'end' : 'start'}}">
                        <div class="d-flex align-items-center">
                            @if($oneMessage['fromMe'] == 1)
                            <div>
                                <span class="text-muted font-size-sm">{{$oneMessage['date_time']}}</span>
                            </div>
                            <div class="symbol symbol-circle symbol-40 ml-3">
                                <img alt="Pic" src="{{$myImage}}" />
                            </div>
                            @else
                            <div class="symbol symbol-circle symbol-40 mr-3">
                                <img alt="Pic" src="{{$chat['image']}}" />
                            </div>
                            <div>
                                <span class="text-muted font-size-sm">{{$oneMessage['date_time']}}</span>
                            </div>
                            @endif
                        </div>
                        <div class="mt-2 rounded p-5 bg-light-{{$oneMessage['fromMe'] == 1 ? 'primary' : 'success'}} text-dark-50 font-weight-bold font-size-lg text-{{$oneMessage['fromMe'] == 1 ? 'right' : 'left'}} max-w-400px">
                            @if($oneMessage['message_type'] == 'text')
                            <livewire:text-msg :msg="$oneMessage" :wire:key="$oneMessage['id']"/>
                            @endif
                        </div>
                    </div>                    
                    @endforeach
                    @endif
                    <div id="kt_scrollDown" class="scrollDown text-right float-right hidden">
                        <span class="fa-icon fa-icon-xl">
                            <i class="la la-angle-double-down text-white"></i>
                        </span>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        
        @if($selected)
        <livewire:send-msg  :wire:key="$chat['id']"/>
        @endif
    </div>
</div>


