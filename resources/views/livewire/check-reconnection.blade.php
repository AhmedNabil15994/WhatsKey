@if((isset($data->haveImage) && $data->haveImage == 1) || (isset($data->tutorials) && !empty($data->tutorials)))
<div class="alert alert-custom alert-success" role="alert"  wire:poll.10s wire:model="CheckReconnection">
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text">{{trans('main.gotQrCode')}}</div>
    <div class="alert-close float-right">
        <a class="btn btn-light-success " href="{{URL::to('/QR')}}">
            {{trans('main.reconnect')}}
        </a>
    </div>
    <div class="clearfix"></div>
</div>
@else
<div class="alert alert-custom" role="alert" wire:poll.10s wire:model="CheckReconnection"></div>
@endif