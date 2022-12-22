<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="msgDetails text-left mt-2">
        @if($msg['fromMe'] == 1 && $msg['sending_status'] != 6)
        <span class="fa-icon">
            @if($msg['sending_status'] == 1)
            <i class="icon-md la la-check"></i>
            @elseif($msg['sending_status'] == 2)
            <i class="icon-md la la-check-double"></i>
            @elseif($msg['sending_status'] == 3)
            <i class="icon-md la la-check-double text-primary"></i>
            @elseif($msg['sending_status'] == 0)
            <i class="icon-md la la-clock"></i>
            @endif
        </span>
        @endif
        <span>{{$msg['date_time']}}</span>
        @if($msg['starred'])
        <span class="fa-icon mx-1"> <i class="icon-md la la-star"></i></span>
        @endif

        @if($msg['labelled'])
        @foreach($msg['labelled'] as $labelObj)
        <span class="catLabel fa-icon" data-toggle="tooltip" data-original-title="{{$labelObj['name_ar']}}"> <i class="icon-md fas fa-tag label-cat{{$labelObj['color_id']}}"></i></span>
        @endforeach
        @endif
    </div>
</div>
