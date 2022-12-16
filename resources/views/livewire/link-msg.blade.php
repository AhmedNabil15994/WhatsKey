<div>
    <div class="link">
        <div class="replyHeader py-3 px-3 mb-3">
            <span class="text-muted d-block">{{$msg['metadata']['title']}}</span>
            <span class="text-muted d-block">{{str_replace('https://','',$msg['metadata']['matchedText'])}}</span>
        </div>
        <div>
            {{$msg['metadata']['description']}} <a href="{{$msg['metadata']['matchedText']}}" target="_blank">{{$msg['metadata']['matchedText']}}</a>
        </div>
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
