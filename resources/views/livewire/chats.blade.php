<div>
    @foreach($chats as $oneChat)
    @php $oneChat  = json_decode(json_encode($oneChat), true); @endphp
    <div>
        <livewire:chat :chat="$oneChat" :wire:key="time().'chat'.$oneChat['id']"/>    
    </div>
    @endforeach
    <div class="spinContainer py-5 text-center hidden">
        <div class="spinner spinner-track spinner-xl spinner-dark mr-15 text-center" style="display: initial;"></div>        
    </div>
</div>