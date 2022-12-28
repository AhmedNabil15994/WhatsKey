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
<div class="card card-custom mb-5">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{ trans('main.actions') }}</h3>
    </div>
    <div class="card-body text-center">
        <a href="#" class="btn btn-light-dark btn-md mr-3 mb-3 btn-pill screen"><i class="la la-mobile-alt"></i>{{ trans('main.screenshot') }}</a>
        <a href="{{ URL::to('/profile/subscription/syncAll') }}" class="btn btn-light-success btn-md mr-3 mb-3 btn-pill"><i class="flaticon2-chat-1"></i>{{ trans('main.syncAll') }}</a>
        <a href="{{ URL::to('/profile/subscription/closeConn') }}" class="btn btn-light-danger btn-md mr-3 mb-3 btn-pill"><i class="la la-close"></i>{{ trans('main.closeConn') }}</a>
        <a href="{{ URL::to('/profile/subscription/read/1') }}" class="btn btn-light-dark btn-md mr-3 mb-3 btn-pill"><i class="la la-check-double"></i>{{ trans('main.readAll') }}</a>
        <a href="{{ URL::to('/profile/subscription/read/0') }}" class="btn btn-light-warning btn-md mr-3 mb-3 btn-pill"><i class="la la-check"></i>{{ trans('main.unreadAll') }}</a>
        <a href="{{ URL::to('/profile/subscription/syncDialogs') }}" class="btn btn-light-info btn-md mr-3 mb-3 btn-pill"><i class="la la-comments"></i>{{ trans('main.syncDialogs') }}</a>
        <a href="{{ URL::to('/profile/subscription/syncContacts') }}" class="btn btn-light-primary btn-md mr-3 mb-3 btn-pill"><i class="la la-user-circle"></i>{{ trans('main.syncContacts') }}</a>
        @if($data->me->isBussines)
        <a href="{{ URL::to('/profile/subscription/syncLabels') }}" class="btn btn-light-info btn-md mr-3 mb-3 btn-pill"><i class="la la-tags"></i>{{ trans('main.syncLabels') }}</a>
        <a href="{{ URL::to('/profile/subscription/syncReplies') }}" class="btn btn-light-warning btn-md mr-3 mb-3 btn-pill"><i class="la la-share"></i>{{ trans('main.syncReplies') }}</a>
        <a href="{{ URL::to('/profile/subscription/syncCollections') }}" class="btn btn-light-success btn-md mr-3 mb-3 btn-pill"><i class="la la-tags"></i>{{ trans('main.syncCatalog') }}</a>
        <a href="{{ URL::to('/profile/subscription/syncProducts') }}" class="btn btn-light-primary btn-md mr-3 mb-3 btn-pill"><i class="la la-product-hunt"></i>{{ trans('main.syncProducts') }}</a>
        <a href="{{ URL::to('/profile/subscription/syncOrders') }}" class="btn btn-light-dark btn-md mr-3 mb-3 btn-pill"><i class="la la-shopping-cart"></i>{{ trans('main.syncOrders') }}</a>
        @endif
        <a href="{{ URL::to('/profile/subscription/restoreAccountSettings') }}" class="btn btn-light-danger btn-md mr-3 mb-3 btn-pill"><i class="la la-trash-alt"></i>{{ trans('main.restoreAccountSettings') }}</a>
    </div>
</div>

@if(isset($data->totalQueueMessages) && $data->totalQueueMessages > 0)
<div class="card card-custom mb-5">
    <div class="card-header">
        <h3 class="card-title">{{ trans('main.messagesQueue') }} ({{$data->totalQueueMessages}})</h3>
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
        <h3 class="card-title">{{ trans('main.blockedUser') }} ({{count($data->blockList)}})</h3>
        {{-- <div class="card-toolbar">
            <a href="{{ URL::to('/profile/subscription/clearMessagesQueue') }}" class="btn btn-sm btn-danger font-weight-bold">
            <i class="la la-trash-alt"></i>{{ trans('main.delete') }}</a>
        </div> --}}
    </div>
    <div class="card-body">
        <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
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
                        <a href="{{ URL::to('/profile/subscription/unBlock/'.$user) }}" class="btn btn-sm btn-success font-weight-bold">
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
        <h3 class="card-title"> {{ trans('main.currentPackage') }}</h3>
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
            @if($data->me->businessProfile->business_hours)
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
            @if($data->me->businessProfile->category)
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
            @if($data->me->businessProfile->description)
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
            @if($data->me->businessProfile->website)
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
