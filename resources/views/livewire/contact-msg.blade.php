<div>
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
