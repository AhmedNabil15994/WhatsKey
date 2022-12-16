<div>
    <div class="lists" style="min-width: 200px;">
        <span class="text-muted d-block">{{$msg['metadata']['title']}}</span>
        <span class="text-muted d-block mt-3 mb-3">{{$msg['metadata']['body']}}</span>
        <span class="text-muted d-block mt-3 mb-3">{{$msg['metadata']['footer']}}</span>
        <hr>
        <a href="#" class="text-center w-100" onclick="Livewire.emit('showModal', {{json_encode($msg)}})">
            {{$msg['metadata']['buttonText']}}
            <span class="fa-icon">
                <i class="la la-list text-primary"></i>
            </span>
        </a>
        <hr>
        <div class="msgDetails text-left mt-2">
            <span class="fa-icon">
                @if($msg['sending_status'] == 1)
                <i class="icon-md la la-check"></i>
                @elseif($msg['sending_status'] == 2)
                <i class="icon-md la la-check-double"></i>
                @elseif($msg['sending_status'] == 3)
                <i class="icon-md la la-check-double text-primary"></i>
                @else
                <i class="icon-md la la-clock"></i>
                @endif
            </span>
            <span>{{$msg['date_time']}}</span>
        </div>
    </div>
</div>