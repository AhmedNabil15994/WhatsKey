<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
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
    </div>
</div>
