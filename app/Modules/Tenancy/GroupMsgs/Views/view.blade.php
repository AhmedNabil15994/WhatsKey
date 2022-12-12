{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')

@endsection
@section('breadcrumbs')
@include('tenant.Layouts.breadcrumb',[
    'breadcrumbs' => [
        [
            'title' => trans('main.menu'),
            'url' => \URL::to('/dashboard')
        ],
        [
            'title' => trans('main.groupMsgs'),
            'url' => \URL::to('/groupMsgs')
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
@if($data->checkAvailBotPlus == 1)
<div class="alert alert-custom alert-dark" role="alert" >
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text">{{trans('main.groupMsgNotify')}}</div>
</div>
@endif

<div class="row stats">    
    <div class="col-md-3">
        <div class="card card-custom bg-primary card-stretch gutter-b">
            <div class="card-body">
                <span class="fa-icon">
                    <i class="icon-3x flaticon-users text-white"></i>
                </span>
                <span class="card-title font-weight-bolder text-white font-size-h2 mb-0 d-block">{{ $data->msg->contacts_count }}</span>
                <span class="font-weight-bold text-white font-size-sm">{{ trans('main.contacts_count') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom bg-info card-stretch gutter-b">
            <div class="card-body">
                <span class="fa-icon">
                    <i class="icon-3x flaticon-paper-plane text-white"></i>
                </span>
                <span class="card-title font-weight-bolder text-white font-size-h2 mb-0 d-block">{{ $data->msg->sent_msgs }}</span>
                <span class="font-weight-bold text-white font-size-sm">{{ trans('main.sent_msgs') }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-custom bg-success card-stretch gutter-b">
            <div class="card-body">
                <span class="fa-icon">
                    <i class="icon-3x flaticon-close text-white"></i>
                </span>
                <span class="card-title font-weight-bolder text-white font-size-h2 mb-0 d-block">{{ $data->msg->unsent_msgs }}</span>
                <span class="font-weight-bold text-white font-size-sm">{{ trans('main.unsent_msgs') }}</span>
            </div>
        </div>
    </div>
     <div class="col-md-3">
        <div class="card card-custom bg-warning card-stretch gutter-b">
            <div class="card-body">
                <span class="fa-icon">
                    <i class="icon-3x fa fa-eye text-white"></i>
                </span>
                <span class="card-title font-weight-bolder text-white font-size-h2 mb-0 d-block">{{ $data->msg->viewed_msgs }}</span>
                <span class="font-weight-bold text-white font-size-sm">{{ trans('main.viewed_msgs') }}</span>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label>{{ trans('main.status') }} :</label>
            <input class="form-control" disabled name="name_ar" value="{{ $data->msg->sent_msgs > 0 ? trans('main.sent') : $data->msg->sent_type }}">
        </div> 
        <div class="form-group">
            <label>{{ trans('main.sender') }} :</label>
            <input class="form-control" disabled name="name_ar" value="{{ $data->phone }}">
        </div> 
        <div class="form-group">
            <label>{{ trans('main.sentDate') }} :</label>
            <input class="form-control" disabled name="name_ar" value="{{ $data->msg->publish_at2 }}">
        </div>
        <div class="form-group">
            <label>{{ trans('main.message_type') }} :</label>
            <input class="form-control" disabled name="message_type" value="{{ $data->msg->message_type_text }}">
        </div> 
        <div class="form-group">
            <label>{{ trans('main.message_content') }} :</label>
            @if($data->msg->message != '')
            <textarea class="form-control" disabled name="name_ar">{{$data->msg->message}}</textarea>
            @else
            <a href="{{$data->msg->file}}" class="btn btn-md btn-success font-weight-bolder" target="_blank"><i class="fa fa-download"></i> {{$data->msg->file_name}}</a>
            @endif
        </div> 
        <div class="card-footer text-right">
            @if(\Helper::checkRules('add-group-message'))
            <a href="{{ URL::to('/groupMsgs/resend/'.$data->msg->id.'/1') }}" class="btn resend btn-primary">{{ trans('main.resend') }}</a>
            @endif
            <a href="{{ URL::to('/groupMsgs') }}" class="btn btn-secondary">{{trans('main.back')}}</a>
        </div>
    </div>
</div>

<div class="card card-custom mt-5">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{ trans('main.recipients') }}</h3>
        <div class="card-toolbar">
            <a href="{{ URL::to('/groupMsgs/resend/'.$data->msg->id.'/2') }}" class="btn mx-1 resend btn-primary">{{ trans('main.resendUnsent') }}</a>   
            <a href="{{ URL::to('/groupMsgs/refresh/'.$data->msg->id) }}" class="btn mx-1 resend btn-secondary">{{ trans('main.refresh2') }}</a>   
        </div>
    </div>
    <div class="card-body">
        <input type="hidden" name="designElems" value="{{ json_encode($data->designElems) }}">
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
            <thead>
                <tr>
                    @foreach($data->designElems['tableData'] as $one)
                    <th>{{ $one['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- <table class="tableBills table table-striped  dt-responsive nowrap w-100">
    <thead>
        <tr>
            <th>{{ trans('main.id') }}</th>
            <th>{{ trans('main.phone') }}</th>
            <th>{{ trans('main.status') }}</th>
            <th>{{ trans('main.date') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data->data as $key => $contact)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{$contact->phone}}</td>
            <td>
                <span class="badge badge-{{ $contact->reportStatus[0] }}">{{ $contact->reportStatus[1] }}</span>
            </td>
            <td>{{ $contact->reportStatus[2] }}</td>
        </tr>
        @endforeach
    </tbody>
</table> 
@include('tenant.Partials.pagination')
--}}

@endsection

@section('scripts')
<script src="{{ asset('assets/tenant/components/addMsg.js') }}"></script>
<script src="{{ asset('assets/tenant/components/datatables.js')}}"></script>           
@endsection