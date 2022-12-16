<div>
    <div class="disappearing">
        <div>{{$msg['body']}}</div>
        
        <div class="msgDetails text-left">
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
    <div style="position:absolute; right: -40px;top: calc(50% - 8px);">
        <span class="fa-icon label labe-md label-rounded label-secondary text-muted" data-toggle="tooltip" data-original-title="{{trans('main.endsAt').' '.(\Carbon\Carbon::createFromTimeStamp(strtotime($msg['metadata']['expirationFormatted']))->diffForHumans())}}">
            <i class="la la-info text-muted"></i>
        </span>
    </div>
</div>
