<div class="flex-row-fluid ml-lg-8" id="kt_chat_content">
    <div class="card card-custom">
        <div class="card-header align-items-center px-4 py-3"> 
        @if($selected)
        <livewire:chat-actions  :name="$chat['name']" :wire:key="$chat['id'].'actions'" />
        @endif
        </div>
        <div class="card-body">
            <div class="scroll scroll-pull scroll-pulls" data-mobile-height="350">
                <div class="messages" id="messages">
                    @if($selected)
                    @foreach(array_reverse($messages) as $oneMessage)
                    @php 
                        $oneMessage = (array) $oneMessage; 
                        $oneMessage['metadata'] = (array) $oneMessage['metadata']; 
                    @endphp
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
                        <div class="mt-2 rounded p-5 bg-{{$oneMessage['fromMe'] == 1 ? 'gray-100' : 'light-success'}} text-dark-50 font-weight-bold font-size-lg text-{{$oneMessage['fromMe'] == 1 ? 'right' : 'left'}} max-w-400px" style="{{$oneMessage['message_type'] == 'disappearing' ? "position: relative;" : ''}}">
                            @if($oneMessage['message_type'] == 'text')
                            <livewire:text-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'image')
                            <livewire:image-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'video')
                            <livewire:video-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'audio')
                            <livewire:audio-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'document')
                            <livewire:file-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'sticker')
                            <livewire:sticker-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'gif')
                            <livewire:gif-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'linkWithPreview')
                            <livewire:link-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'location')
                            <livewire:location-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'contact')
                            <livewire:contact-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'disappearing')
                            <livewire:disappearing-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'mention')
                            <livewire:mention-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'buttons')
                            <livewire:buttons-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'template')
                            <livewire:template-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'list')
                            <livewire:list-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>
                            <livewire:list-sections :msg="$oneMessage" :wire:key="'modal'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'groupInvitation')
                            <livewire:group-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'catalog')
                            <livewire:catalog-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'product')
                            <livewire:product-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'order')
                            <livewire:order-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

                            @elseif($oneMessage['message_type'] == 'poll')
                            <livewire:poll-msg :msg="$oneMessage" :wire:key="'msg'.$oneMessage['id']"/>

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
        <div class="card-footer px-6 align-items-center">
        @if($selected)
        <livewire:send-msg  :wire:key="$chat['id']"/>
        @endif
        </div>
    </div>
</div>


