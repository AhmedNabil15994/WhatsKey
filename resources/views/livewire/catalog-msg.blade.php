<div>
    <div class="catalog">
        <div class="kanban-item">
            <div class="replyHeader py-3 px-3 d-flex align-items-center">
                {{-- <div class="symbol symbol-success mx-3">
                    <div class="bg-gray-200" style="width: 50px;height: 50px;"></div>
                </div> --}}
                <div class="d-flex flex-column align-items-start">
                    <span class="text-dark font-weight-bold mb-1">{{ trans('main.viewUserCatalog',['user' => ucwords(\App\Models\User::first()->name)]) }}</span>
                    <span class="text-dark font-weight-bold mb-1">{{trans('main.viewUserP')}}</span>
                </div>
            </div>
            <div>
                <p class="mt-3 mb-0">{{str_replace($msg['metadata']['matchedText'],'',$msg['body'])}}</p>
                <a href="{{$msg['metadata']['matchedText']}}" target="_blank">{{$msg['metadata']['matchedText']}}</a>
            </div>
            <hr>
            <div class="row mb-3 text-center">
                <a href="{{$msg['metadata']['matchedText']}}" class="w-100">{{trans('main.viewCatalog')}}</a>
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
