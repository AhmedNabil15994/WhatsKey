{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="screen">
    .AdditionsSub .title a{
        padding: 0;
    }
    .cartSub{
        padding: 40px 15px 25px;
        width: 100%;
        margin-right: 0;
        margin-left: 0;
    }
    .carts .row{
        margin: 0;
        width: 100%;
    }
    .modal-full-width{
        width: 75%;
    }
    .modal-header .close{
        margin-top: -25px;
    }
    .form-group.MeasuresText{
        margin: auto;
        width: 100%;
    }
    .form-group.MeasuresText label,
    .form-group{
        margin-bottom: 0;
    }
    .AdditionsSub .title a.edit{
        width: 120px;
    }
    .queues .desc2{
        max-height: 350px;
        overflow-y: scroll;
    }
    .queues .desc2 .row div.col-md-2,
    .queues .desc2 .row div.col-md-3,
    .queues .desc2 .row div.col-md-6{
        padding: 10px;
    }
    .row.mains{
        border-bottom: 1px solid #EFEFEF;
    }
    .row.mains .title{
        border: 0;
    }
    .queues .desc2 .row div.col-md-2.phone{
        direction: ltr;
    }   
    .queues .desc2 .row {
        border-bottom: 1px solid #EFEFEF;
    }
    .packages .card-title{
        margin-bottom: 0;
    }
</style>
@endsection
@section('breadcrumbs')
@include('tenant.Layouts.breadcrumb',[
    'breadcrumbs' => [
        [
            'title' => trans('main.menu'),
            'url' => \URL::to('/dashboard')
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')

<div class="row">
    <div class="col-9">
        <div class="card card-custom mb-5">
            <div class="card-header">
                <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }} mx-1"></i> {{ trans('main.actions') }}</h3>
            </div>
            <div class="card-body text-center">
                <a href="#" class="btn btn-light-dark btn-md mr-3 mb-3 btn-pill screen" id="screenshot"><i class="la la-mobile-alt"></i>{{ trans('main.screenshot') }}</a>
                <a href="{{ URL::to('/profile/subscription/syncAll') }}" class="btn btn-light-success btn-md mr-3 mb-3 btn-pill"><i class="flaticon2-chat-1"></i>{{ trans('main.syncAll') }}</a>
                <a href="{{ URL::to('/profile/subscription/closeConn') }}" class="btn btn-light-danger btn-md mr-3 mb-3 btn-pill"><i class="la la-close"></i>{{ trans('main.closeConn') }}</a>
                <a href="{{ URL::to('/profile/subscription/read/1') }}" class="btn btn-light-dark btn-md mr-3 mb-3 btn-pill"><i class="la la-check-double"></i>{{ trans('main.readAll') }}</a>
                <a href="{{ URL::to('/profile/subscription/read/0') }}" class="btn btn-light-warning btn-md mr-3 mb-3 btn-pill"><i class="la la-check"></i>{{ trans('main.unreadAll') }}</a>
                <a href="{{ URL::to('/profile/subscription/syncDialogs') }}" class="btn btn-light-info btn-md mr-3 mb-3 btn-pill"><i class="la la-comments"></i>{{ trans('main.syncDialogs') }}</a>
                <a href="{{ URL::to('/profile/subscription/syncContacts') }}" class="btn btn-light-primary btn-md mr-3 mb-3 btn-pill"><i class="la la-user-circle"></i>{{ trans('main.syncContacts') }}</a>
                <a href="{{ URL::to('/profile/subscription/resyncAll') }}" class="btn btn-light-dark btn-md mr-3 mb-3 btn-pill"><i class="la la-user-circle"></i>{{ trans('main.resyncAll') }}</a>
                @if(isset($data->me) && isset($data->me->isBussines) && $data->me->isBussines)
                <a href="{{ URL::to('/profile/subscription/syncLabels') }}" class="btn btn-light-info btn-md mr-3 mb-3 btn-pill"><i class="la la-tags"></i>{{ trans('main.syncLabels') }}</a>
                <a href="{{ URL::to('/profile/subscription/syncReplies') }}" class="btn btn-light-warning btn-md mr-3 mb-3 btn-pill"><i class="la la-share"></i>{{ trans('main.syncReplies') }}</a>
                <a href="{{ URL::to('/profile/subscription/syncCollections') }}" class="btn btn-light-success btn-md mr-3 mb-3 btn-pill"><i class="la la-tags"></i>{{ trans('main.syncCatalog') }}</a>
                <a href="{{ URL::to('/profile/subscription/syncProducts') }}" class="btn btn-light-primary btn-md mr-3 mb-3 btn-pill"><i class="la la-product-hunt"></i>{{ trans('main.syncProducts') }}</a>
                <a href="{{ URL::to('/profile/subscription/syncOrders') }}" class="btn btn-light-dark btn-md mr-3 mb-3 btn-pill"><i class="la la-shopping-cart"></i>{{ trans('main.syncOrders') }}</a>
                @endif
                <a href="{{ URL::to('/profile/subscription/restoreAccountSettings') }}" class="btn btn-light-danger btn-md mr-3 mb-3 btn-pill"><i class="la la-trash-alt"></i>{{ trans('main.restoreAccountSettings') }}</a>
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="card card-custom mb-5">
            <div class="card-header">
                <h3 class="card-title"><i class="la la-cog icon-xl mx-1"></i> {{ trans('main.channel_settings') }}</h3>
            </div>
            <div class="card-body p-2">
                <div class="form-group row m-0">
                    <label class="col-8 col-form-label">{{trans('main.channelSettings_disableGroupsReply')}}</label>
                    <div class="col-4">
                        <span class="switch switch-outline switch-sm switch-icon switch-success">
                            <label>
                                <input type="checkbox" name="disableGroupsReply" {{$data->channelSettings['disableGroupsReply'] != null && $data->channelSettings['disableGroupsReply'] == 1 ? 'checked' : ''}}/>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>  
                <div class="form-group row m-0">
                    <label class="col-8 col-form-label">{{trans('main.channelSettings_disableDialogsArchive')}}</label>
                    <div class="col-4">
                        <span class="switch switch-outline switch-sm switch-icon switch-success">
                            <label>
                                <input type="checkbox" name="disableDialogsArchive" {{$data->channelSettings['disableDialogsArchive'] != null && $data->channelSettings['disableDialogsArchive'] == 1 ? 'checked' : ''}}/>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>  
                <div class="form-group row m-0">
                    <label class="col-8 col-form-label">{{trans('main.channelSettings_disableReceivingCalls')}}</label>
                    <div class="col-4">
                        <span class="switch switch-outline switch-sm switch-icon switch-success">
                            <label>
                                <input type="checkbox" name="disableReceivingCalls" {{$data->channelSettings['disableReceivingCalls'] != null && $data->channelSettings['disableReceivingCalls'] == 1 ? 'checked' : ''}}/>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>  
                <div class="form-group row mx-4 mb-2">
                    <label>{{trans('main.channelSettings_contactsNameType')}}</label>
                    <select class="form-control" data-toggle="select2" name="contactsNameType">
                        <option>{{trans('main.choose')}}</option>
                        <option value="1" {{$data->channelSettings['contactsNameType'] != null && $data->channelSettings['contactsNameType'] == 1 ? 'selected' : ''}}>{{trans('main.contactsNameType1')}}</option>
                        <option value="2" {{$data->channelSettings['contactsNameType'] != null && $data->channelSettings['contactsNameType'] == 2 ? 'selected' : ''}}>{{trans('main.contactsNameType2')}}</option>
                        <option value="3" {{$data->channelSettings['contactsNameType'] != null && $data->channelSettings['contactsNameType'] == 3 ? 'selected' : ''}}>{{trans('main.contactsNameType3')}}</option>
                    </select>
                </div>  
            </div>
        </div>
    </div>
</div>

@if(isset($data->totalQueueMessages) && $data->totalQueueMessages > 0)
<div class="card card-custom mb-5">
    <div class="card-header">
        <h3 class="card-title"><i class="la la-envelope icon-xl mx-1"></i> {{ trans('main.messagesQueue') }} ({{$data->totalQueueMessages}})</h3>
        <div class="card-toolbar">
            <a href="{{ URL::to('/profile/subscription/clearMessagesQueue') }}" class="btn btn-sm btn-danger font-weight-bold">
            <i class="la la-trash-alt"></i>{{ trans('main.delete') }}</a>
        </div>
    </div>
    <div class="card-body">
        <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
            <thead>
                <tr>
                    <th title="Field #1">{{ trans('main.phone') }}</th>
                    <th title="Field #2">{{ trans('main.messageType') }}</th>
                    <th title="Field #3">{{ trans('main.date') }}</th>
                    <th title="Field #4">{{ trans('main.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->queuedMessages as $msg)
                @php 
                $newMsgData = \Helper::reformMessage($msg);
                @endphp
                <tr>
                    <td>{{$newMsgData->chatId}}</td>
                    <td>{{$newMsgData->type}}</td>
                    <td>{{$newMsgData->last_try}}</td>
                    <td>{{$newMsgData->status}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if(isset($data->blockList) && $data->blockList > 0)
<div class="card card-custom mb-5">
    <div class="card-header">
        <h3 class="card-title"><i class="la la-users icon-xl mx-1"></i> {{ trans('main.blockedUser') }} ({{count($data->blockList)}})</h3>
    </div>
    <div class="card-body">
        <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatables">
            <thead>
                <tr>
                    <th title="Field #1">{{ trans('main.id') }}</th>
                    <th title="Field #2">{{ trans('main.phone') }}</th>
                    <th title="Field #3">{{ trans('main.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->blockList as $key => $user)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{str_replace('@c.us','',$user)}}</td>
                    <td>
                        <a href="{{ URL::to('/profile/subscription/unBlock/'.$user) }}" class="btn btn-sm btn-outline-danger font-weight-bold">
                            <i class="la la-ban"></i>{{ trans('main.unBlock') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card card-custom gutter-b mb-5">
    <div class="card-header">
        <h3 class="card-title"><i class="la la-id-card icon-xl mx-1"></i> {{ trans('main.currentPackage') }}</h3>
        <div class="card-toolbar">
            @if(\Helper::checkRules('changeSubscription'))
            <a href="{{ URL::to('/profile/subscription/memberships') }}" class="btn btn-sm btn-dark font-weight-bold mx-1">{{ trans('main.resubscribe_b2') }}</a>
            @if((int)date('d',strtotime($data->subscription->end_date)) != 1)
            <a href="{{ URL::to('/profile/subscription/transferPayment') }}" class="btn btn-sm btn-dark font-weight-bold mx-1">{{ trans('main.transferPayment') }}</a> 
            @endif
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center flex-wrap mb-5">
            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="la la-id-card display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">{{trans('main.packageName')}}</span>
                    <span class="font-weight-bolder font-size-h5">
                    <span class="text-dark-50 font-weight-bold"></span>{{ $data->subscription->package_name }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="flaticon-confetti display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">{{trans('main.substatus')}}</span>
                    <span class="font-weight-bolder font-size-h5">
                    <span class="text-dark-50 font-weight-bold"></span>{{ $data->subscription->channelStatus == 1 ? trans('main.active') : trans('main.notActive') }}</span>
                </div>
            </div>
            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="la la-money-bill-alt display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">{{trans('main.nextMillestone')}}</span>
                    <span class="font-weight-bolder font-size-h5">
                    <span class="text-dark-50 font-weight-bold"></span>{{ $data->subscription->end_date }}</span>
                </div>
            </div>

            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="la la-calendar display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">{{trans('main.substartDate')}}</span>
                    <span class="font-weight-bolder font-size-h5">
                    <span class="text-dark-50 font-weight-bold"></span>{{ $data->subscription->start_date }}</span>
                </div>
            </div>

            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="la la-calendar display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">{{trans('main.subendDate')}}</span>
                    <span class="font-weight-bolder font-size-h5">
                    <span class="text-dark-50 font-weight-bold"></span>{{ $data->subscription->end_date }}</span>
                </div>
            </div>
        </div>
        <div class="separator separator-solid mb-8"></div>
        <div class="d-flex mb-9">
            <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                <div class="symbol symbol-50 symbol-lg-120">
                    <img src="{{ @$data->me->image == null ? asset('assets/tenant/images/def_user.svg') : @$data->me->image }}" alt="image">
                </div>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between flex-wrap mt-1">
                    <div class="d-flex mr-3">
                        <a href="#" class="text-dark-75 text-hover-primary font-size-h5 font-weight-bold mr-3">{{@$data->me->me->name}}</a>
                        <a href="#">
                            <i class="flaticon2-correct text-success font-size-h5"></i>
                        </a>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-between mt-1">
                    <div class="d-flex flex-column flex-grow-1 pr-8">
                        <div class="d-flex flex-wrap mb-4">
                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                <i class="flaticon2-phone mr-2 font-size-lg"></i> {{@$data->me->me->phone}}
                            </a>
                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold mr-lg-8 mr-5 mb-lg-0 mb-2">
                                <i class="flaticon2-calendar-3 mr-2 font-size-lg"></i> # {{ $data->channel != null ? $data->channel->instanceId : '' }}
                            </a>
                            <a href="#" class="text-dark-50 text-hover-primary font-weight-bold">
                                <i class="flaticon2-calendar mr-2 font-size-lg"></i> {{ $data->status != null ? $data->status->created_at : '' }}
                            </a>
                        </div>

                        <div class="row">
                            <div class="col-6 col-md-4">
                                <div class="mb-8 d-flex flex-column">
                                    <span class="font-weight-bold text-dark-50 mb-4">{{ trans('main.phone_status') }}</span>
                                    <span class="text-muted font-weight-bolder font-size-lg">{{ $data->status != null ? $data->status->statusText : '' }}</span>
                                </div>
                            </div>

                            <div class="col-6 col-md-4">
                                <div class="mb-8 d-flex flex-column">
                                    <span class="font-weight-bold text-dark-50 mb-4">{{ trans('main.msgSync') }}</span>
                                    <span class="text-muted font-weight-bolder font-size-lg">{{ $data->allDialogs > 0 ? trans('main.synced') : trans('main.notSynced') }}</span>
                                </div>
                            </div>

                            <div class="col-6 col-md-4">
                                <div class="mb-8 d-flex flex-column">
                                    <span class="font-weight-bold text-dark-50 mb-4">{{ trans('main.contSync') }}</span>
                                    <span class="text-muted font-weight-bolder font-size-lg">{{ $data->contactsCount > 0 ? trans('main.synced') : trans('main.notSynced') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center w-25 flex-fill float-right mt-lg-12 mt-8">
                        <span class="font-weight-bold text-dark-75">{{ trans('main.leftDays') }}</span>
                        <div class="progress progress-xs mx-3 w-100">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $data->channel != null ? $data->channel->rate : '' }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <span class="font-weight-bolder text-dark">{{ $data->channel != null ? $data->channel->leftDays : '' }} {{ trans('main.day') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-solid"></div>
        <div class="d-flex align-items-center flex-wrap mt-8">
            @if(isset($data->me) && isset($data->me->businessProfile) && $data->me->businessProfile->business_hours)
            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="flaticon-piggy-bank display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">{{trans('main.business_hours')}}</span>
                    <span class="font-weight-bolder font-size-h5">
                    <span class="text-dark-50 font-weight-bold"></span>{{implode(',',$data->me->businessProfile->business_hours)}}</span>
                </div>
            </div>
            @endif
            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="flaticon-confetti display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">{{trans('main.status')}}</span>
                    <span class="font-weight-bolder font-size-h5">
                    <span class="text-dark-50 font-weight-bold"></span>{{@$data->me->status->status}}</span>
                </div>
            </div>
            @if(isset($data->me) && isset($data->me->businessProfile) &&  $data->me->businessProfile->category)
            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="flaticon-pie-chart display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column text-dark-75">
                    <span class="font-weight-bolder font-size-sm">{{trans('main.category')}}</span>
                    <span class="font-weight-bolder font-size-h5">
                    <span class="text-dark-50 font-weight-bold"></span>{{$data->me->businessProfile->category}}</span>
                </div>
            </div>
            @endif
            @if(isset($data->me) && isset($data->me->businessProfile) &&  $data->me->businessProfile->description)
            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="flaticon-file-2 display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column flex-lg-fill">
                    <span class="text-dark-75 font-weight-bolder font-size-sm">{{trans('main.description')}}</span>
                    <a href="#" class="text-primary font-weight-bolder">{{$data->me->businessProfile->description}}</a>
                </div>
            </div>
            @endif
            @if(isset($data->me) && isset($data->me->businessProfile) &&  $data->me->businessProfile->website)
            <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                <span class="mr-4">
                    <i class="flaticon2-website display-4 text-muted font-weight-bold"></i>
                </span>
                <div class="d-flex flex-column">
                    <span class="text-dark-75 font-weight-bolder font-size-sm">{{trans('main.website')}}</span>
                    <a href="#" class="text-primary font-weight-bolder">{{implode(',',$data->me->businessProfile->website)}}</a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card card-custom card-stretch gutter-b">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bolder">
                    <span class="svg-icon svg-icon-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"></rect>
                                <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                    </span>
                    {{trans('main.addons')}}
                </h3>
                <div class="card-toolbar">
                    @if(\Helper::checkRules('changeSubscription'))
                    <a href="{{ URL::to('/profile/subscription/addons') }}" class="mx-1 btn btn-sm btn-outline-success font-weight-bold"><i class="la la-edit icon-md"></i> {{ trans('main.edit') }}</a>
                    <a href="{{ URL::to('/profile/subscription/addons/disableAutoInvoice') }}" class="mx-1 btn btn-sm btn-outline-info font-weight-bold">
                        @if($data->subscription->disableAddonAutoInvoice == null || $data->subscription->disableAddonAutoInvoice == 0)
                        <i class="la la-times icon-md"></i> {{ trans('main.disableAutoInvoice') }}
                        @else
                        <i class="la la-check icon-md"></i> {{ trans('main.enableAutoInvoice') }}
                        @endif
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body pt-0">
                @foreach($data->subscription->membership_addons as $membershipAddon)
                <div class="d-flex align-items-center bg-light-success rounded p-5 mb-5">
                    <span class="svg-icon svg-icon-lg svg-icon-success mr-5">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"></path>
                                <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                    </span>
                    <div class="d-flex flex-column flex-grow-1 mr-2">
                        <a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">{{$membershipAddon->title}}</a>
                        <span class="text-muted font-weight-bold">{{$data->subscription->start_date . ' - ' . $data->subscription->end_date}}</span>
                    </div>
                    <div class="d-flex flex-column mr-2 text-right">
                        <span class="label label-xs label-{{$data->subscription->channelStatus == 1 ? 'success' : 'danger'}} label-inline font-weight-bold py-4 float-right">{{ $data->subscription->channelStatus == 1 ? trans('main.active') : trans('main.notActive') }}</span>
                    </div>
                </div>
                @endforeach

                @foreach($data->subscription->addons as $addon)
                <div class="d-flex align-items-center bg-light-success rounded p-5 mb-5">
                    <span class="svg-icon svg-icon-lg svg-icon-success mr-5">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"></path>
                                <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                    </span>
                    <div class="d-flex flex-column flex-grow-1 mr-2">
                        <a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">{{$addon->Addon->title}}</a>
                        <span class="text-muted font-weight-bold">{{$addon->start_date . ' - ' . $addon->end_date}}</span>
                    </div>
                    <div class="d-flex flex-column mr-2 text-right">
                        @php $statusColors = ['danger','success','warning']; @endphp
                        @if(\Helper::checkRules('changeSubscription'))
                        <div class="dropdown dropdown-inline ml-2 mb-2" data-placement="left">
                            <a href="#" class="btn btn-clean btn-hover-primary btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ki ki-bold-more-hor text-dark-75"></i>
                            </a>
                            <div class="dropdown-menu p-0 m-0 dropdown-menu-xs dropdown-menu-right">
                                <ul class="navi navi-hover">
                                    @if($addon->status == 2)
                                    <li class="navi-item">
                                        <a href="{{ URL::to('/profile/subscription/updateAddonStatus/'.$addon->id.'/3') }}" class="navi-link px-2">
                                            <span class="navi-text"><i class="la la-refresh icon-md"></i> {{trans('main.renew')}}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($addon->status == 0)
                                    <li class="navi-item">
                                        <a href="{{ URL::to('/profile/subscription/updateAddonStatus/'.$addon->id.'/1') }}" class="navi-link px-2">
                                            <span class="navi-text"><i class="la la-check icon-md"></i> {{trans('main.enable')}}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($addon->status == 1)
                                    <li class="navi-item">
                                        <a href="{{ URL::to('/profile/subscription/updateAddonStatus/'.$addon->id.'/4') }}" class="navi-link px-2">
                                            <span class="navi-text"><i class="la la-close icon-md"></i> {{trans('main.disable')}}</span>
                                        </a>
                                    </li>
                                    @endif
                                    <li class="navi-item">
                                        <a href="{{ URL::to('/profile/subscription/updateAddonStatus/'.$addon->id.'/5') }}" class="navi-link px-2">
                                            <span class="navi-text"><i class="la la-trash icon-md"></i> {{trans('main.delete')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif
                        <span class="label label-xs label-{{$statusColors[$addon->status]}} label-inline font-weight-bold py-4 float-right">{{$addon->statusText}}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card card-custom">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bolder">
                    <span class="svg-icon menu-icon svg-icon-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M11.7573593,15.2426407 L8.75735931,15.2426407 C8.20507456,15.2426407 7.75735931,15.6903559 7.75735931,16.2426407 C7.75735931,16.7949254 8.20507456,17.2426407 8.75735931,17.2426407 L11.7573593,17.2426407 L11.7573593,18.2426407 C11.7573593,19.3472102 10.8619288,20.2426407 9.75735931,20.2426407 L5.75735931,20.2426407 C4.65278981,20.2426407 3.75735931,19.3472102 3.75735931,18.2426407 L3.75735931,14.2426407 C3.75735931,13.1380712 4.65278981,12.2426407 5.75735931,12.2426407 L9.75735931,12.2426407 C10.8619288,12.2426407 11.7573593,13.1380712 11.7573593,14.2426407 L11.7573593,15.2426407 Z" fill="#000000" opacity="0.3" transform="translate(7.757359, 16.242641) rotate(-45.000000) translate(-7.757359, -16.242641)"></path>
                                <path d="M12.2426407,8.75735931 L15.2426407,8.75735931 C15.7949254,8.75735931 16.2426407,8.30964406 16.2426407,7.75735931 C16.2426407,7.20507456 15.7949254,6.75735931 15.2426407,6.75735931 L12.2426407,6.75735931 L12.2426407,5.75735931 C12.2426407,4.65278981 13.1380712,3.75735931 14.2426407,3.75735931 L18.2426407,3.75735931 C19.3472102,3.75735931 20.2426407,4.65278981 20.2426407,5.75735931 L20.2426407,9.75735931 C20.2426407,10.8619288 19.3472102,11.7573593 18.2426407,11.7573593 L14.2426407,11.7573593 C13.1380712,11.7573593 12.2426407,10.8619288 12.2426407,9.75735931 L12.2426407,8.75735931 Z" fill="#000000" transform="translate(16.242641, 7.757359) rotate(-45.000000) translate(-16.242641, -7.757359)"></path>
                                <path d="M5.89339828,3.42893219 C6.44568303,3.42893219 6.89339828,3.87664744 6.89339828,4.42893219 L6.89339828,6.42893219 C6.89339828,6.98121694 6.44568303,7.42893219 5.89339828,7.42893219 C5.34111353,7.42893219 4.89339828,6.98121694 4.89339828,6.42893219 L4.89339828,4.42893219 C4.89339828,3.87664744 5.34111353,3.42893219 5.89339828,3.42893219 Z M11.4289322,5.13603897 C11.8194565,5.52656326 11.8194565,6.15972824 11.4289322,6.55025253 L10.0147186,7.96446609 C9.62419433,8.35499039 8.99102936,8.35499039 8.60050506,7.96446609 C8.20998077,7.5739418 8.20998077,6.94077682 8.60050506,6.55025253 L10.0147186,5.13603897 C10.4052429,4.74551468 11.0384079,4.74551468 11.4289322,5.13603897 Z M0.600505063,5.13603897 C0.991029355,4.74551468 1.62419433,4.74551468 2.01471863,5.13603897 L3.42893219,6.55025253 C3.81945648,6.94077682 3.81945648,7.5739418 3.42893219,7.96446609 C3.0384079,8.35499039 2.40524292,8.35499039 2.01471863,7.96446609 L0.600505063,6.55025253 C0.209980772,6.15972824 0.209980772,5.52656326 0.600505063,5.13603897 Z" fill="#000000" opacity="0.3" transform="translate(6.014719, 5.843146) rotate(-45.000000) translate(-6.014719, -5.843146)"></path>
                                <path d="M17.9142136,15.4497475 C18.4664983,15.4497475 18.9142136,15.8974627 18.9142136,16.4497475 L18.9142136,18.4497475 C18.9142136,19.0020322 18.4664983,19.4497475 17.9142136,19.4497475 C17.3619288,19.4497475 16.9142136,19.0020322 16.9142136,18.4497475 L16.9142136,16.4497475 C16.9142136,15.8974627 17.3619288,15.4497475 17.9142136,15.4497475 Z M23.4497475,17.1568542 C23.8402718,17.5473785 23.8402718,18.1805435 23.4497475,18.5710678 L22.0355339,19.9852814 C21.6450096,20.3758057 21.0118446,20.3758057 20.6213203,19.9852814 C20.2307961,19.5947571 20.2307961,18.9615921 20.6213203,18.5710678 L22.0355339,17.1568542 C22.4260582,16.76633 23.0592232,16.76633 23.4497475,17.1568542 Z M12.6213203,17.1568542 C13.0118446,16.76633 13.6450096,16.76633 14.0355339,17.1568542 L15.4497475,18.5710678 C15.8402718,18.9615921 15.8402718,19.5947571 15.4497475,19.9852814 C15.0592232,20.3758057 14.4260582,20.3758057 14.0355339,19.9852814 L12.6213203,18.5710678 C12.2307961,18.1805435 12.2307961,17.5473785 12.6213203,17.1568542 Z" fill="#000000" opacity="0.3" transform="translate(18.035534, 17.863961) scale(1, -1) rotate(45.000000) translate(-18.035534, -17.863961)"></path>
                            </g>
                        </svg>
                    </span>
                    {{trans('main.extraQuotas')}}
                </h3>
                <div class="card-toolbar">
                    @if(\Helper::checkRules('changeSubscription'))
                    <a href="{{ URL::to('/profile/subscription/extraQuotas') }}" class="btn btn-sm btn-outline-success font-weight-bold"><i class="la la-edit icon-md"></i> {{ trans('main.edit') }}</a>
                    <a href="{{ URL::to('/profile/subscription/extraQuotas/disableAutoInvoice') }}" class="mx-1 btn btn-sm btn-outline-info font-weight-bold">
                        @if($data->subscription->disableExtraQuotaAutoInvoice == null || $data->subscription->disableExtraQuotaAutoInvoice == 0)
                        <i class="la la-times icon-md"></i> {{ trans('main.disableAutoInvoice2') }}
                        @else
                        <i class="la la-check icon-md"></i> {{ trans('main.enableAutoInvoice2') }}
                        @endif
                    </a>
                    @endif
                </div>
            </div>
            <div class="card-body pt-0">
                @php
                    $colors = ['success','warning','dark'];
                    $icons = ['wechat','user-tie','cloud-upload-alt'];
                @endphp
                @foreach($data->subscription->extra_quotas as $extra_quota)
                <div class="d-flex align-items-center bg-light-{{$colors[$extra_quota->ExtraQuota->extra_type-1]}} rounded p-5 mb-5">
                    <span class="fa-icon-xl mr-5">
                        <i class="la la-{{$icons[$extra_quota->ExtraQuota->extra_type-1]}} icon-2x text-{{$colors[$extra_quota->ExtraQuota->extra_type-1]}}"></i>
                    </span>
                    <div class="d-flex flex-column flex-grow-1 mr-2">
                        <a href="#" class="font-weight-bold text-dark-75 text-hover-primary font-size-lg mb-1">{{$extra_quota->ExtraQuota->title}}</a>
                        <span class="text-muted font-weight-bold">{{$extra_quota->start_date . ' - ' . $extra_quota->end_date}}</span>
                    </div>
                    <div class="d-flex flex-column mr-2 text-right">
                        @if(\Helper::checkRules('changeSubscription'))
                        <div class="dropdown dropdown-inline ml-2 mb-2" data-placement="left">
                            <a href="#" class="btn btn-clean btn-hover-primary btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ki ki-bold-more-hor text-dark-75"></i>
                            </a>
                            <div class="dropdown-menu p-0 m-0 dropdown-menu-xs dropdown-menu-right">
                                <ul class="navi navi-hover">
                                    @if($extra_quota->status == 2)
                                    <li class="navi-item">
                                        <a href="{{ URL::to('/profile/subscription/updateExtraQuotaStatus/'.$extra_quota->id.'/3') }}" class="navi-link px-2">
                                            <span class="navi-text"><i class="la la-refresh icon-md"></i> {{trans('main.renew')}}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($extra_quota->status == 0)
                                    <li class="navi-item">
                                        <a href="{{ URL::to('/profile/subscription/updateExtraQuotaStatus/'.$extra_quota->id.'/1') }}" class="navi-link px-2">
                                            <span class="navi-text"><i class="la la-check icon-md"></i> {{trans('main.enable')}}</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if($extra_quota->status == 1)
                                    <li class="navi-item">
                                        <a href="{{ URL::to('/profile/subscription/updateExtraQuotaStatus/'.$extra_quota->id.'/4') }}" class="navi-link px-2">
                                            <span class="navi-text"><i class="la la-close icon-md"></i> {{trans('main.disable')}}</span>
                                        </a>
                                    </li>
                                    @endif
                                    <li class="navi-item">
                                        <a href="{{ URL::to('/profile/subscription/updateExtraQuotaStatus/'.$extra_quota->id.'/5') }}" class="navi-link px-2">
                                            <span class="navi-text"><i class="la la-trash icon-md"></i> {{trans('main.delete')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif
                        <span class="label label-xs label-{{$colors[$extra_quota->status]}} label-inline font-weight-bold py-4 float-right">{{$extra_quota->statusText}}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="card card-custom gutter-b" style="height: 130px">
            <div class="card-body d-flex flex-column">
                <div class="flex-grow-1">
                    <div class="text-dark-50 font-weight-bold">{{ trans('main.dialogs') }}</div>
                    <div class="font-weight-bolder font-size-h3">{{ $data->allDialogs }}</div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 94%;" aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card card-custom gutter-b" style="height: 130px">
            <div class="card-body d-flex flex-column">
                <div class="flex-grow-1">
                    <div class="text-dark-50 font-weight-bold">{{ trans('main.contacts') }}</div>
                    <div class="font-weight-bolder font-size-h3">{{ $data->contactsCount }}</div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card card-custom gutter-b" style="height: 130px">
            <div class="card-body d-flex flex-column">
                <div class="flex-grow-1">
                    <div class="text-dark-50 font-weight-bold">{{ trans('main.sentMessages') .' '.trans('main.today') }} </div>
                    <div class="font-weight-bolder font-size-h3">{{ $data->sentMessages .'/'. \Session::get('dailyMessageCount') }}</div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{$data->sentMessages / \Session::get('dailyMessageCount')}}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    
     <div class="col-xl-6">
        <div class="card card-custom gutter-b" style="height: 130px">
            <div class="card-body d-flex flex-column">
                <div class="flex-grow-1">
                    <div class="text-dark-50 font-weight-bold">{{ trans('main.messages') }}</div>
                    <div class="font-weight-bolder font-size-h3">{{ $data->messages }}</div>
                </div>
                <div class="progress progress-xs">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('modals')
@include('tenant.Partials.screen_modal')
@endsection

@endsection


@section('scripts')
<script src="{{ asset('assets/tenant/components/subscription.js') }}" type="text/javascript"></script>
<script src="{{asset('assets/tenant/js/pages/crud/ktdatatable/base/html-table.js')}}"></script>
@endsection
