<div>
    @foreach($chats as $oneChat)
    @php $oneChat = (array) $oneChat; @endphp
    <livewire:chat :chat="$oneChat" :wire:key="$oneChat['id']"/>
    @endforeach
    <div class="spinContainer py-5 text-center hidden">
        <div class="spinner spinner-track spinner-xl spinner-dark mr-15 text-center" style="display: initial;"></div>        
    </div>
</div>