<div>
    @php 
        $msg = (array) $msg; 
        $id = array_reverse(explode('_',$msg['id']))[0];
    @endphp
    <div class="messageItem d-flex flex-column mb-5 align-items-{{$msg['fromMe'] == 1 ? 'end' : 'start'}}" id="{{$id}}">
        <div class="mt-2 rounded p-3 bg-{{$msg['fromMe'] == 1 ? 'white' : 'light-success'}} text-dark-50 font-weight-bold font-size-lg text-{{$msg['fromMe'] == 1 ? 'right' : 'left'}} max-w-400px" style="{{$msg['message_type'] == 'disappearing' ? "position: relative;" : ''}}">
            <livewire:message-reply :wire:key="'msgReply'.$id" :msg="$msg" :chatName="$chatName"/>
            @if($msg['message_type'] == 'text')
            <livewire:messages.text-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'image')
            <livewire:messages.image-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'video')
            <livewire:messages.video-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'audio')
            <livewire:messages.audio-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'document')
            <livewire:messages.file-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'sticker')
            <livewire:messages.sticker-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'gif')
            <livewire:messages.gif-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'linkWithPreview')
            <livewire:messages.link-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'location')
            <livewire:messages.location-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'contact')
            <livewire:messages.contact-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'disappearing')
            <livewire:messages.disappearing-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'mention')
            <livewire:messages.mention-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'buttons')
            <livewire:messages.buttons-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'template')
            <livewire:messages.template-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'list')
            <livewire:messages.list-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>
            <livewire:messages.list-sections :msg="$msg" :wire:key="'modal'.$id"/>

            @elseif($msg['message_type'] == 'groupInvitation')
            <livewire:messages.group-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'catalog')
            <livewire:messages.catalog-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'product')
            <livewire:messages.product-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'order')
            <livewire:messages.order-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @elseif($msg['message_type'] == 'poll')
            <livewire:messages.poll-msg :msg="$msg" :wire:key="'msg'.$id" :chatName="$chatName"/>

            @endif
            <livewire:message-details :wire:key="'msgDetails'.$id" :msg="$msg" :noMargin="1"/>
        </div>
    </div>     
</div>
