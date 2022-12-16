<div>
    <div class="group">
        <div class="kanban-item">
            <div class="replyHeader py-3 px-3 d-flex align-items-center">
                <div class="symbol symbol-success mr-3">
                    <img alt="Pic" src="{{asset('assets/tenant/images/groupImage.jpeg')}}">
                </div>
                <div class="d-flex flex-column align-items-start">
                    <span class="text-dark font-weight-bold mb-1">{{$msg['metadata']['title']}}</span>
                    <span class="text-dark font-weight-bold mb-1">{{trans('main.groupChatInvite')}}</span>
                </div>
            </div>
            <div>
                <p class="mt-3 mb-0">{{str_replace($msg['metadata']['matchedText'],'',$msg['body'])}}</p>
                <a href="{{$msg['metadata']['matchedText']}}" target="_blank">{{$msg['metadata']['matchedText']}}</a>
            </div>
            <hr>
            <div class="row mb-3 text-center">
                <a href="{{$msg['metadata']['matchedText']}}" class="w-100">{{trans('main.viewGroup')}}</a>
            </div>
            <hr>
        </div>
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
</div>
