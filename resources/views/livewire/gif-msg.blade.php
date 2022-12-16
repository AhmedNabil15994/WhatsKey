<div>
    <div class="gif">
        <a href="{{$msg['body']}}" target="_blank">
            <video class="{{$msg['caption'] != '' ? 'mb-3' : '' }} d-block" width="300" height="200" controls>
                <source src="{{$msg['body']}}" type="video/mp4">
            </video>
        </a>
        @if($msg['caption'] != '')
        <span>{{$msg['caption']}}</span>
        @endif
        <div class="msgDetails text-left {{$msg['caption'] == '' ? 'mt-2' : '' }}">
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
