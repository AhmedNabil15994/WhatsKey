<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="order">
        <div class="kanban-item">
            <div class="replyHeader py-3 px-3 d-flex align-items-center">
                <div class="symbol symbol-success mr-3">
                    <img alt="Pic" src="{{asset('assets/tenant/images/groupImage.jpeg')}}">
                </div>
                <div class="d-flex flex-column align-items-start">
                    <span class="text-dark font-weight-bold mb-1" style="white-space: unset;">
                        <span class="fa-icon"><i class="icon-xl la la-shopping-cart"></i> {{$msg['metadata']['itemCount'].' '.trans('main.items')}}</span>
                    </span>
                    <span class="text-muted font-weight-bold mb-1" dir="ltr">{{  $msg['metadata']['price']. ' ' . $msg['metadata']['currency'].' '. trans('main.estTotal')}}</span>
                </div>
            </div>
            <hr>
            <div class="row mb-3 text-center">
                <a href="#" class="w-100">{{trans('main.viewRecCart')}}</a>
            </div>
            <hr>
        </div>
    </div>
</div>
