<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="disappearing">
        <div class="text-dark">{{$msg['body']}}</div>
    </div>
    <div style="position:absolute; right: -40px;top: calc(50% - 8px);">
        <span class="fa-icon label labe-md label-rounded label-secondary text-muted" data-toggle="tooltip" data-original-title="{{trans('main.endsAt').' '.(\Carbon\Carbon::createFromTimeStamp(strtotime($msg['metadata']['expirationFormatted']))->diffForHumans())}}">
            <i class="la la-info text-muted"></i>
        </span>
    </div>
</div>
