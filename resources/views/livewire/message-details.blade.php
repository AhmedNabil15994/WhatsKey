<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="msgDetails text-left mt-2">
        @if($msg['fromMe'] == 1)
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
        @endif
        <span>{{$msg['date_time']}}</span>
    </div>
</div>
