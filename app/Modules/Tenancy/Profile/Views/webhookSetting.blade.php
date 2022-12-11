@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="screen">
    .check-title{
        margin-left: 25px;
        margin-right: 25px;
        margin-top: 15px;
    }
    html[dir="ltr"] .form input[type="checkbox"]{
        left: 0;
    }
    html[dir="rtl"] .form input[type="checkbox"]{
        right: 20px;
    }
</style>
@endsection
@section('breadcrumbs')
@include('tenant.Layouts.breadcrumb',[
    'breadcrumbs' => [
        [
            'title' => trans('main.dashboard'),
            'url' => \URL::to('/dashboard')
        ],
        [
            'title' => trans('main.website_setting'),
            'url' => \URL::to('/profile/apiSetting')
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection
@section('content')
<div class="card card-custom formNumbers">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ URL::to('/profile/postWebhookSetting') }}">
            @csrf
            <div class="form-group">
                <label>{{ trans('main.status') }} :</label>                            
                <div class="checkbox-inline">
                    <label class="checkbox checkbox-outline checkbox-success">
                        <input type="checkbox" name="webhook_on" {{ \App\Models\Variable::getVar('WEBHOOK_ON') == 1 ? 'checked' : '' }}/>
                        <span></span>
                    </label>
                </div>
            </div> 
            <div class="form-group">
                <label>{{ trans('main.webhookURL') }} :</label>
                <input class="form-control" type="text" class="form-control" value="{{ \App\Models\Variable::getVar('WEBHOOK_URL') }}" name="webhook_url" placeholder="{{ trans('main.webhookURL') }}">
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.save')}}</button>
                <a href="{{ URL::to('/profile/apiSetting') }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </form>
    </div>
</div>
@endsection


@section('scripts')

@endsection
