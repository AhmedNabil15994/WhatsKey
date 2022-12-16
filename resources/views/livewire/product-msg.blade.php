<div>
    <div class="product">
        <div class="kanban-item">
            <div class="symbol symbol-success">
                <div class="bg-gray-200" style="width: 250px;height: 250px;"></div>
            </div>
            <div class="replyHeader py-3 px-3 d-flex align-items-center">                 
                <div class="d-flex flex-column align-items-start">
                    <span class="text-dark font-weight-bold mb-1">{{ $msg['metadata']['title'] }}</span>
                    <span class="text-muted font-weight-bold mb-1">{{ $msg['metadata']['price']. ' ' . $msg['metadata']['currency'] }}</span>
                </div>
            </div>
            <hr>
            <div class="row mb-3 text-center">
                <a href="#" class="w-100">{{trans('main.view')}}</a>
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
