<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="contact">
        <div class="kanban-item">
            <div class="d-flex align-items-center">
                <div class="symbol symbol-success mr-3">
                    <img alt="Pic" src="{{asset('assets/tenant/images/def_user.svg')}}">
                </div>
                <div class="d-flex flex-column align-items-start">
                    <span class="text-dark font-weight-bold mb-1">{{$msg['metadata']['name']}}</span>
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-6 text-center" style="border-left: 1px solid #CCC;">
                    <a href="#">Add To Group</a>
                </div>
                <div class="col-md-6 text-center">
                    <a href="#">Message</a>
                </div>
            </div>
            <hr>
        </div>
    </div>
</div>
