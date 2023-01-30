@extends('tenant.Layouts.master')
@section('title',trans('main.dashboard'))
@section('pageName',trans('main.dashboard'))

@section('styles')
<style type="text/css" media="screen">
    .timer .nextPrev .btnNext{
        padding: 10px;
    }
    .timer.times{
        margin:30px auto;
        border-radius: 10px;
        background-color: #00bfb5;
        text-align: center;
        overflow: hidden;
        max-width:360px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        min-height: 60px;
        color: #fff;
        margin-top: 0;
    }
    .timer.times i{
        margin-left:5px;
        position: relative;
        top: 1px;
    }
    .timer.times .titleTimer{
        margin-bottom: 30px;
        width: 100%;
        color: #ffffff;
    }
    .datatable.datatable-default>.datatable-table>.datatable-body .datatable-row>.datatable-cell{
        white-space:pre-line;
        text-align: center;
    }
    .datatable.datatable-default>.datatable-table>.datatable-head .datatable-row>.datatable-cell.datatable-cell-sort{
        text-align: center;
    }
    html[dir="rtl"] .timeline.timeline-6:before{
        right: 100px;
    }
    html[dir="ltr"] .timeline.timeline-6:before{
        left: 100px;
    }
</style>
@endsection

@section('content')
<div class="stats">
    
    @if(\App\Models\Variable::getVar('hasJob') == 1)
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="timer text-center card p-5">
                <img src="{{ asset('assets/tenant/images/checkImg.png') }}" alt="">
                <h2 class="titleTimer">{{ trans('main.inPrgo') }}</h2>
                <span class="time mCounter font-weight-bolder font-size-h3" dir="ltr" data-minutes="1">01:00</span>
                <div class="desc">
                    {{ trans('main.preparingAccount') }}
                </div>
                <div class="Attention">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32.001" height="39.205" viewBox="0 0 32.001 39.205">
                      <path id="XMLID_560_" d="M61.928,39.205A13.2,13.2,0,0,1,50.92,33.488L43.514,22.639A3.1,3.1,0,0,1,45.8,17.884a6.285,6.285,0,0,1,5.776,2.656V7.555a3.7,3.7,0,0,1,3.767-3.63,3.888,3.888,0,0,1,1.085.153V3.855a4.008,4.008,0,0,1,8.01,0V4.2a4.032,4.032,0,0,1,1.228-.19,3.844,3.844,0,0,1,3.91,3.765v.685a4.177,4.177,0,0,1,1.371-.23A3.983,3.983,0,0,1,75,12.135V26.791c0,6.845-5.864,12.415-13.073,12.415ZM46.5,20.526a3.555,3.555,0,0,0-.4.022c-.274.031-.561.3-.372.579l7.409,10.853,0,.006a10.519,10.519,0,0,0,8.786,4.537c5.73,0,10.391-4.367,10.391-9.734V12.135a1.38,1.38,0,0,0-2.741,0v8.779a1.341,1.341,0,0,1-2.682,0V7.78a1.237,1.237,0,0,0-2.456,0V20.734a1.341,1.341,0,0,1-2.682,0V3.855a1.332,1.332,0,0,0-2.646,0v16.7a1.341,1.341,0,0,1-2.682,0v-13a1.095,1.095,0,0,0-2.17,0V24.484a1.355,1.355,0,0,1-2.4.817l-2.692-3.5A3.359,3.359,0,0,0,46.5,20.526Z" transform="translate(-43)"></path>
                    </svg>
                    {{ trans('main.dontClose') }}
                </div>
            </div>
        </div>
        @livewire('activate-account')
        @section('scripts')
        <script src="{{ asset('assets/tenant/components/countDown.js') }}"></script>
        @endsection
    </div>
    @elseif(Session::has('invoice_id') && Session::get('invoice_id') != 0)
    @php
    $transferObj = \App\Models\BankTransfer::where('user_id',ROOT_ID)->where('status',1)->orderBy('invoice_id','DESC')->first();
    @endphp
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="timer timer2 text-center card p-5">
                <img class="mb-5" src="{{ asset('assets/tenant/images/checkImg.png') }}" alt="">
                <div class="desc font-size-h3 mb-5">
                    {{ trans('main.resubscribe_p') }}
                </div>
                <div class="totalConfirm mb-5">
                    <center>
                        <div class="nextPrev clearfix">
                            <a href="{{ URL::to('/invoices/view/'.Session::get('invoice_id')) }}" class="mx-2 btn btn-outline-primary font-weight-bolder text-uppercase btn-primary py-4 px-6">{{ trans('main.resubscribe_b1') }}</a>
                            <a href="{{ URL::to('/profile/subscription/memberships') }}" class="mx-2 btn btn-outline-success font-weight-bolder text-uppercase btn-primary py-4 px-6">{{ trans('main.resubscribe_b2') }}</a>
                        </div>
                    </center>
                </div>
            </div> 
        </div>
    </div>
    @else

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card card-custom bg-{{$data->sendStatus > 0 ? 'success' : 'danger'}} gutter-b" style="height: 200px;">
                        <div class="card-body">
                            <div id="kt_tiles_widget_201_chart" style="height: 150px"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card card-custom bg-{{$data->serverStatus > 0 ? 'success' : 'danger'}} gutter-b" style="height: 200px;">
                        <div class="card-body">
                            <div id="kt_tiles_widget_202_chart" style="height: 150px"></div>
                        </div>
                    </div>
                </div>

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
                                <div class="text-dark-50 font-weight-bold">{{ trans('main.sentMessages') }}</div>
                                <div class="font-weight-bolder font-size-h3">{{ $data->sentMessages }}</div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                 <div class="col-xl-6">
                    <div class="card card-custom gutter-b" style="height: 130px">
                        <div class="card-body d-flex flex-column">
                            <div class="flex-grow-1">
                                <div class="text-dark-50 font-weight-bold">{{ trans('main.incomeMessages') }}</div>
                                <div class="font-weight-bolder font-size-h3">{{ $data->incomingMessages }}</div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-header border-0">
                            <h3 class="card-title font-weight-bolder text-dark">{{ trans('main.lastContactsAdded') }}</h3>
                        </div>
                        <div class="card-body pt-0" style="height: 440px;overflow-y: scroll;">
                            @foreach($data->lastContacts as $contact)
                            <div class="mb-6">
                                <!--begin::Content-->
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between w-100">
                                        <div class="d-flex flex-column align-items-cente py-2 w-75">
                                            <a href="#" class="text-dark-75 font-weight-bold text-hover-primary font-size-lg mb-1" dir="ltr">{{ str_replace('@','',$contact->name) }}</a>
                                            <span class="text-muted font-weight-bold">{{ str_replace('@','',$contact->phone2) }}</span>
                                        </div>
                                        @if( $contact->created_at2[0] != '1970-01-01')
                                        <span class="label label-lg label-light label-inline font-weight-bold py-4">{{ $contact->created_at2[0] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{ trans('main.msgsArchive') }}</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-7">
                <div class="row align-items-center">
                    <div class="col-lg-9 col-xl-8">
                        <div class="row align-items-center">
                            <div class="col-md-6 my-2 my-md-0">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="{{trans('main.search')}}..." id="kt_datatable_search_query" />
                                    <span>
                                        <i class="flaticon2-search-1 text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
                <thead>
                    <tr>
                        <th title="Field #1">{{ trans('main.dialog') }}</th>
                        <th title="Field #2">{{ trans('main.messageContent') }}</th>
                        <th title="Field #3">{{ trans('main.status') }}</th>
                        <th title="Field #4">{{ trans('main.extra_type') }}</th>
                        <th title="Field #5">{{ trans('main.sentDate') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->data as $message)
                    <tr>
                        <td><a href="#" class="numbStyle"><i class="flaticon-phone-call"></i> {{ $message->dialog }}</a></td>
                        <td>
                            @if($message->body != null && (strpos(' https',ltrim($message->body)) !== false || filter_var(trim($message->body), FILTER_VALIDATE_URL)))
                            📷
                            @else
                            @if($message->message_type == 'contact')
                            <p>{{ $message->contact_name }}</p>
                            <p>{{ $message->contact_number }}</p>
                            @else
                            {{$message->body}}
                            @endif
                            @endif
                        </td>
                        <td>{{ $message->sending_status_text }}</td>
                        <td>
                            @if($message->fromMe)
                                <i class="flaticon-reply text-success"></i>
                            @else
                                <i class="flaticon-speech-bubble-1 text-danger"></i>
                            @endif 
                        </td>
                        <td>
                            <i class="flaticon-calendar"></i> {{ $message->created_at_day }}  {{ $message->created_at_time }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
  
    @if(!empty($data->logs))
    <div class="card card-custom mt-7">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <h3 class="card-title row w-100">
                <span class="font-weight-bolder text-dark col-6">{{ trans('main.activityLog') }}</span>
                <span class="text-muted mt-3 font-weight-bolder font-size-h4 col-6 text-right">{{count($data->logs)}}</span>
            </h3>
        </div>
        <div class="card-body pt-4">
            <div class="timeline timeline-6 mt-3">
                @foreach($data->logs as $log)
                <div class="timeline-item align-items-start">
                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg w-100px">{{ $log->created_at2 }}</div>
                    <div class="timeline-badge">
                        <i class="fa fa-genderless text-{{$log->type == 1 ? 'success' : ($log->type == 2 ? 'danger' : 'primary')}} icon-xl"></i>
                    </div>
                    <div class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">{{ $log->user }} <span>{{ $log->typeText }}</span> <span>{{str_replace('@c.us','',str_replace('@g.us','',$log->chatId))}}</span></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @endif          
    
    
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{asset('assets/tenant/js/pages/crud/ktdatatable/base/html-table.js')}}"></script>
<script>
    $(function(){
        function _initTilesWidget(id,size,text) {
            var element = document.getElementById(id);
            if (!element) {
                return;
            }
            var inverseColor = size == 0 ? KTApp.getSettings()['colors']['theme']['inverse']['danger'] : KTApp.getSettings()['colors']['theme']['inverse']['success'];
            var backgroundColor = size == 0 ? KTApp.getSettings()['colors']['theme']['base']['danger'] : KTApp.getSettings()['colors']['theme']['base']['success'];
            var options = {
                series: [size],
                chart: {
                    height: 250,
                    type: 'radialBar',
                    offsetY: 0,
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,

                        hollow: {
                            margin: 0,
                            size: "70%"
                        },
                        dataLabels: {
                            showOn: "always",
                            name: {
                                show: true,
                                fontSize: "13px",
                                fontWeight: "400",
                                offsetY: -5,
                                color: KTApp.getSettings()['colors']['gray']['gray-300']
                            },
                            value: {
                                color: inverseColor,
                                fontSize: "22px",
                                fontWeight: "bold",
                                offsetY: -40,
                                show: true
                            }
                        },
                        track: {
                            background: KTUtil.changeColor(backgroundColor, -7),
                            strokeWidth: '100%'
                        }
                    }
                },
                colors: [inverseColor],
                stroke: {
                    lineCap: "round",
                },
                labels: [text]
            };

            var chart = new ApexCharts(element, options);
            chart.render();
        }
        _initTilesWidget('kt_tiles_widget_201_chart',"{{$data->sendStatus}}","{{ trans('main.sendStatus') }}");
        _initTilesWidget('kt_tiles_widget_202_chart',"{{$data->serverStatus}}","{{ trans('main.serverStatus') }}");        
    });
</script>
@endsection