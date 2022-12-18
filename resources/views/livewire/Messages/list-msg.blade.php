<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="lists" style="min-width: 200px;">
        <span class="text-dark d-block">{{$msg['metadata']['title']}}</span>
        <span class="text-muted d-block mt-3 mb-3">{{$msg['metadata']['body']}}</span>
        <span class="text-dark d-block mt-3 mb-3">{{$msg['metadata']['footer']}}</span>
        <hr>
        <a href="#" class="text-center w-100" onclick="Livewire.emit('showModal', {{json_encode($msg)}})">
            {{$msg['metadata']['buttonText']}}
            <span class="fa-icon">
                <i class="la la-list text-primary"></i>
            </span>
        </a>
        <hr>
    </div>
</div>