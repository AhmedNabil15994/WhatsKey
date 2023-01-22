{{-- Extends layout --}}
@extends('central.Layouts.Dashboard.master')
@section('title',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css">
    body{
        overflow-x: hidden;
    }
    form{
        width: 100%;
    }
    .inbox-item-text{
        white-space: pre;
    }
    .btn.btn-md.waves-effect{
        font-weight: bold;
        color: #FFF;
    }
    .desc .btn.btn-md{
        color: #FFF;
        margin-bottom: 5px;
    }
    .modal-full-width{
        width: 75%;
        max-width: unset;
    }
    #compensationModal td .form-control{
        border:  1px solid #ecf0f3 !important;
    }
    .ui-datepicker{
        z-index: 99999999 !important;
    }
    #compensationModal .modal-dialog{
        max-width: fit-content;
    }
    form .select2-container{
        z-index: 20 !important;
    }
    .modal-open .select2-container {
        z-index: 99999 ;
    }
    #compensationModal{
        z-index: 9999999 !important;
    }
    .addons table td a.btn,
    .extraQuotas table td a.btn,
    .tickets table td a.btn,
    .invoices table td a.btn{
        width: 25px;
        height: 25px;
        padding: 5px;
    }
</style>
@endsection

{{-- Content --}}

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-xl-4">
            <div class="card text-center" style="padding: 20px;">
                <img src="{{ $data->data->photo }}" class="rounded-circle avatar-lg img-thumbnail"
                    alt="profile-image">

                <h4 class="mb-3">{{ ucwords($data->data->name) }}</h4>
                <a href="{{ URL::to('/clients/invLogin/'.$data->data->id) }}" target="_blank" class="btn btn-success btn-md waves-effect mb-2 waves-light"><i class="fas fa-sign-in-alt"></i> {{ trans('main.invLogin') }}</a>
                <a class="btn btn-md btn-primary shareDays waves-effect mb-2 waves-light" data-toggle="modal" data-target="#transferDaysModal"> <i class="fa fa-share"></i> {{ trans('main.add_days') }}</a>
                <a href="{{ URL::to('/clients/pinCodeLogin/'.$data->data->id) }}" target="_blank" class="btn btn-info btn-md waves-effect mb-2 waves-light"><i class="fas fa-sign-in-alt"></i> {{ trans('main.pinCodeLogin') }}</a>
                <a class="btn btn-md btn-warning compensation waves-effect mb-2 waves-light" data-toggle="modal" data-target="#compensationModal"> <i class="fa fa-gift"></i> {{ trans('main.compensate') }}</a>

                <div class="text-left mt-3">
                    <p class="text-muted mb-2 font-13"><strong>{{ trans('main.name') }} :</strong> <span class="ml-2">{{ $data->data->name }}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>{{ trans('main.phone') }} :</strong><span class="ml-2">{{ $data->data->phone }}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>{{ trans('main.email') }} :</strong> <span class="ml-2 ">{{ $data->data->email }}</span></p>

                    <p class="text-muted mb-2 font-13"><strong>{{ trans('main.company_name') }} :</strong> <span class="ml-2 ">{{ $data->data->company }}</span></p>
                    <p class="text-muted mb-2 font-13"><strong>{{ trans('main.domain') }} :</strong> <span class="ml-2 ">{{ $data->data->domain }}</span></p>

                    <p class="text-muted mb-1 font-13"><strong>{{ trans('main.channel') }} :</strong> <span class="ml-2"> {{ @$data->channel->name }}</span></p>
                    <p class="text-muted mb-1 font-13"><strong>{{ trans('main.subscriptionPeriod') }} :</strong> <span class="ml-2"> {{ @$data->channel->start_date }} - {{ @$data->channel->end_date }}</span></p>
                    <p class="text-muted mb-1 font-13"><strong>{{ trans('main.leftDays') }} :</strong> <span class="ml-2"> {{  @$data->channel->leftDays  }} {{ trans('main.day') }}</span> </p>
                </div>
            </div> <!-- end card-box -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="plan-card text-center">
                                <i class="fas fa-comments plan-icon text-primary"></i>
                                <h6 class="text-drak text-uppercase mt-2">{{ trans('main.messages') }}</h6>
                                <h2 class="mb-2">{{ $data->allMessages }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="plan-card text-center">
                                <i class="fas fa-share plan-icon text-primary"></i>
                                <h6 class="text-drak text-uppercase mt-2">{{ trans('main.sentMessages') }}</h6>
                                <h2 class="mb-2">{{ $data->sentMessages }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="plan-card text-center">
                                <i class="fas fa-envelope plan-icon text-primary"></i>
                                <h6 class="text-drak text-uppercase mt-2">{{ trans('main.incomeMessages') }}</h6>
                                <h2 class="mb-2">{{ $data->incomingMessages }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="plan-card text-center">
                                <i class="fas fa-address-book plan-icon text-primary"></i>
                                <h6 class="text-drak text-uppercase mt-2">{{ trans('main.contacts') }}</h6>
                                <h2 class="mb-2">{{ $data->contactsCount }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-xl-8">
            

            <div class="card" style="padding: 20px;"> 
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-2">
                                        <img src="{{ asset('assets/dashboard/assets/images/def_user.svg') }}" width="100%" alt="">
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            @if(isset($data->me) && isset($data->me->name))
                                            <div class="row">
                                                <div class="d-flex mr-3 mb-3" dir="ltr">
                                                    <a href="#" class="text-dark font-size-h5 font-weight-bold mr-1">{{ @$data->me->name }}</a>
                                                    <a href="#">
                                                        <i class="fas fa-check-circle badge-outline-success"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            <!--begin::Content-->
                                            <div class="d-flex flex-wrap justify-content-between mt-1">
                                               <div class="row w-100">
                                                    <div class="col-sm-4">
                                                        <a class="text-dark-50 text-hover-primary font-weight-bold">
                                                            <b>{{ @$data->channel->name }}</b>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <a class="text-dark-50 text-hover-primary font-weight-bold">
                                                            {{ trans('main.phone') }} : <b>{{ @str_replace('@c.us', '', $data->me->phone) }}</b>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <a class="text-dark-50 text-hover-primary font-weight-bold">
                                                            {{ trans('main.connection_date') }}: <b style="direction: ltr;display: inline-block;">{{ @$data->status->created_at }}</b>
                                                        </a>
                                                    </div>
                                                </div>
                                                <hr class="w-100">
                                                <div class="row w-100">
                                                    <div class="col-sm-4">
                                                        <a class="text-dark-50 text-hover-primary font-weight-bold">
                                                            {{ trans('main.phone_status') }} : <b><div class="badge badge-lg badge-success badge-inline" style="margin-top: 10px">{{ @$data->status->statusText }}</div></b>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-4 pt-1">
                                                        <a class="text-dark-50 text-hover-primary font-weight-bold">
                                                            {{ trans('main.msgSync') }} : <b><div class="badge badge-lg badge-success badge-inline">{{ $data->allMessages > 0 ? trans('main.synced') : trans('main.notSynced') }}</div></b>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-4 pt-1">
                                                        <a class="text-dark-50 text-hover-primary font-weight-bold">
                                                            {{ trans('main.contSync') }} : <b><div class="badge badge-lg badge-success badge-inline">{{ $data->contactsCount > 0 ? trans('main.synced') : trans('main.notSynced') }}</div></b>
                                                        </a>
                                                    </div>
                                                </div>
                                                <hr class="w-100">
                                                <div class="d-flex align-items-center w-25 flex-fill float-right mt-lg-12 mt-8">
                                                    <span class="font-weight-bold text-dark-75">{{ trans('main.leftDays') }}</span>
                                                    <div class="progress progress-xs mx-3 w-100">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ @$data->channel->rate }}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="80"></div>
                                                    </div>
                                                    <span class="font-weight-bolder text-dark">{{ @$data->channel->leftDays }} {{ trans('main.day') }}</span>
                                                </div>
                                            </div>
                                            <!--end::Content-->
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card body-->
                        </div> <!-- end card -->
                    </div><!-- end col-->
                </div>
            </div>

            <div class="card">
                <ul class="nav nav-pills navtab-bg nav-justified" style="padding: 20px;">
                    <li class="nav-item">
                        <a href="#settings" data-toggle="tab" aria-expanded="true" class="nav-link active">
                            {{ trans('main.personalInfo') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#prods" data-toggle="tab" aria-expanded="false" class="nav-link">
                            {{ trans('main.subscription') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#aboutme" data-toggle="tab" aria-expanded="false" class="nav-link">
                            {{ trans('main.payment_setting') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#timeline" data-toggle="tab" aria-expanded="false" class="nav-link">
                            {{ trans('main.settings') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#channel_settings" data-toggle="tab" aria-expanded="false" class="nav-link">
                            {{ trans('main.channel_settings') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tickets" data-toggle="tab" aria-expanded="false" class="nav-link">
                            {{ trans('main.tickets') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#invoices" data-toggle="tab" aria-expanded="false" class="nav-link">
                            {{ trans('main.invoices') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content"  style="padding: 20px;">
                    <div class="tab-pane show active" id="settings">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-account-circle mr-1"></i> {{ trans('main.personalInfo') }}</h5>
                        <form action="{{ URL::to('/clients/view/'.$data->data->id.'/updatePersonalInfo') }}" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.name') }}</label>
                                        <input type="text" class="form-control" value="{{$data->data->name}}" name="name" placeholder="{{ trans('main.name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.phone') }}</label>
                                        <input type="hidden" name="phone">
                                        <input id="telephone" type="tel" value="{{'+'.$data->data->phone}}" class="form-control teles">
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.email') }}</label>
                                        <input type="email" class="form-control" value="{{$data->data->email}}" name="email" placeholder="{{ trans('main.email') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="userpassword">{{ trans('main.password') }}</label>
                                        <input type="password" class="form-control" name="password" placeholder="{{ trans('main.password') }}">
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-office-building mr-1"></i> {{ trans('main.company_info') }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="companyname">{{ trans('main.company_name') }}</label>
                                        <input type="text" class="form-control" value="{{$data->data->company}}" name="company" placeholder="{{ trans('main.company_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cwebsite">{{ trans('main.domain') }}</label>
                                        <input type="text" class="form-control" value="{{$data->data->domain}}" name="domain" placeholder="{{ trans('main.domain') }}">
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->
                            <button class="btn btn-success updatePersonalInfo float-right my-2">{{ trans('main.edit') }}</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                    <div class="tab-pane" id="prods">
                        <h5 class="mb-4 text-uppercase"><i class="fab fa-product-hunt mr-1"></i>{{ trans('main.subscription') }}</h5>
                        <form action="{{ URL::to('/clients/view/'.$data->data->id.'/updateSubscription') }}" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.packages') }} :</label>
                                        <select name="membership_id" class="form-control" data-toggle="select2">
                                            <option value="">{{ trans('main.choose') }}</option>
                                            @foreach($data->memberships as $membership)
                                            @if($membership->monthly_price != 0)
                                            <option value="{{ $membership->id }}" {{ $membership->id == $data->data->membership_id ? 'selected' : '' }} data-area="{{ $membership->monthly_after_vat }}" data-cols="{{ $membership->annual_after_vat }}">{{ $membership->title }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.subscriptionPeriod') }} :</label>
                                        <select name="duration_type" class="form-control" data-toggle="select2">
                                            <option value="">{{ trans('main.choose') }}</option>
                                            <option value="1" {{ $data->data->duration_type == 1 ? 'selected' : '' }}>{{ trans('main.monthly') }}</option>
                                            <option value="2" {{ $data->data->duration_type == 2 ? 'selected' : '' }}>{{ trans('main.yearly') }}</option>
                                            <option value="3" {{ $data->data->duration_type == 3 ? 'selected' : '' }}>{{ trans('main.demo') }}</option>
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.start_date') }} :</label>
                                        <input class="form-control datepickerInput" value="{{ date('Y-m-d',strtotime(@$data->channel->start_date)) }}" name="start_date" placeholder="{{ trans('main.start_date') }}">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.end_date') }} :</label>
                                        <input class="form-control datepickerInput" value="{{ date('Y-m-d',strtotime(@$data->channel->end_date)) }}" name="end_date" placeholder="{{ trans('main.end_date') }}">
                                    </div> 
                                </div>
                            </div>
                            <button class="btn btn-success float-right my-2">{{ trans('main.edit') }}</button>
                            <div class="clearfix"></div>

                            <hr class="w-100 mb-4">
                            <div class="row addons w-100 m-0">
                                <div class="row w-100 m-0">
                                    <div class="col-9 p-0">
                                        <h5 class="mb-4 text-uppercase"><i class=" fas fa-star mr-1"></i>{{ trans('main.addons') }}</h5>
                                    </div>                                    
                                    <div class="col-3 p-0 text-right">
                                        <a href="#addonsModal" data-toggle="modal" class="btn btn-success btn-xs"> <i class="fa fa-pencil-alt"></i> {{trans('main.edit') . ' ' . trans('main.addons')}}</a>
                                    </div>
                                </div>
                                <table class="data table table-striped no-margin mb-4">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('main.id') }}</th>
                                            <th>{{ trans('main.name') }}</th>
                                            <th>{{ trans('main.subscriptionPeriod') }}</th>
                                            <th>{{ trans('main.start_date') }}</th>
                                            <th>{{ trans('main.end_date') }}</th>
                                            <th>{{ trans('main.status') }}</th>
                                            <th>{{ trans('main.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($data->addonsData) > 0)
                                        @foreach($data->addonsData as $oneAddon)
                                        <tr>
                                            <td>{{$oneAddon->id}}</td>
                                            <td>{{$oneAddon->Addon->title}}</td>
                                            <td>{{$oneAddon->duration_type == 1 ? trans('main.monthly') : trans('main.yearly')}}</td>
                                            <td class="start_date">{{$oneAddon->start_date}}</td>
                                            <td class="end_date">{{$oneAddon->end_date}}</td>
                                            <td>{{$oneAddon->statusText}}</td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-xs updateUserAddon" data-toggle="tooltip" data-original-title="{{trans('main.edit')}}" data-id="{{$oneAddon->id}}" data-duration="{{$oneAddon->duration_type}}" data-status="{{$oneAddon->status}}" data-addon="{{$oneAddon->Addon->id}}"><i class="fas fa-pencil-alt"></i></a>
                                                <a href="{{URL::to('/clients/view/'.$data->data->id.'/delete/1/'.$oneAddon->addon_id)}}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.delete')}}"><i class="fas fa-trash"></i></a>
                                                @if($oneAddon->status == 0 || $oneAddon->status == 2)
                                                <a href="{{URL::to('/clients/view/'.$data->data->id.'/enable/1/'.$oneAddon->addon_id)}}" class="btn btn-dark btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.enable')}}"><i class="fas fa-check"></i></a>
                                                @elseif($oneAddon->status == 1)
                                                <a href="{{URL::to('/clients/view/'.$data->data->id.'/disable/1/'.$oneAddon->addon_id)}}" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.disable')}}"><i class="fas fa-times"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="7">{{ trans('main.noDataFound') }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr class="w-100 mb-4">
                            <div class="row extraQuotas w-100 m-0">
                                <div class="row w-100 m-0">
                                    <div class="col-9 p-0">
                                        <h5 class="mb-4 text-uppercase"><i class=" fas fa-star mr-1"></i>{{ trans('main.extraQuotas') }}</h5>
                                    </div>                                    
                                    <div class="col-3 p-0 text-right">
                                        <a href="#extraQuotasModal" data-toggle="modal" class="btn btn-success btn-xs"> <i class="fa fa-pencil-alt"></i> {{trans('main.edit') . ' ' . trans('main.extraQuotas')}}</a>
                                    </div>
                                </div>
                                <table class="data table table-striped no-margin mb-4">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('main.id') }}</th>
                                            <th>{{ trans('main.name') }}</th>
                                            <th>{{ trans('main.subscriptionPeriod') }}</th>
                                            <th>{{ trans('main.start_date') }}</th>
                                            <th>{{ trans('main.end_date') }}</th>
                                            <th>{{ trans('main.status') }}</th>
                                            <th>{{ trans('main.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($data->extraQuotasData) > 0)
                                        @foreach($data->extraQuotasData as $oneExtraQuota)
                                        <tr>
                                            <td>{{$oneExtraQuota->id}}</td>
                                            <td>{{$oneExtraQuota->ExtraQuota->title}}</td>
                                            <td>{{$oneExtraQuota->duration_type == 1 ? trans('main.monthly') : trans('main.yearly')}}</td>
                                            <td class="start_date">{{$oneExtraQuota->start_date}}</td>
                                            <td class="end_date">{{$oneExtraQuota->end_date}}</td>
                                            <td>{{$oneExtraQuota->statusText}}</td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-xs updateUserExtraQuota" data-toggle="tooltip" data-original-title="{{trans('main.edit')}}" data-id="{{$oneExtraQuota->id}}" data-duration="{{$oneExtraQuota->duration_type}}" data-status="{{$oneExtraQuota->status}}" data-addon="{{$oneExtraQuota->ExtraQuota->id}}"><i class="fas fa-pencil-alt"></i></a>
                                                <a href="{{URL::to('/clients/view/'.$data->data->id.'/delete/2/'.$oneExtraQuota->extra_quota_id)}}" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.delete')}}"><i class="fas fa-trash"></i></a>
                                                @if($oneExtraQuota->status == 0 || $oneExtraQuota->status == 2)
                                                <a href="{{URL::to('/clients/view/'.$data->data->id.'/enable/2/'.$oneExtraQuota->extra_quota_id)}}" class="btn btn-dark btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.enable')}}"><i class="fas fa-check"></i></a>
                                                @elseif($oneExtraQuota->status == 1)
                                                <a href="{{URL::to('/clients/view/'.$data->data->id.'/disable/2/'.$oneExtraQuota->extra_quota_id)}}" class="btn btn-warning btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.disable')}}"><i class="fas fa-times"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="7">{{ trans('main.noDataFound') }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="aboutme">
                        <h5 class="mb-4 text-uppercase"><i class="mdi mdi-briefcase mr-1"></i>{{ trans('main.payment_setting') }}</h5>
                        <form action="{{ URL::to('/clients/view/'.$data->data->id.'/updatePaymentInfo') }}" method="post" accept-charset="utf-8">
                        @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.address') }} :</label>
                                        <input class="form-control" name="address" value="{{ (!empty($data->paymentInfo) ? $data->paymentInfo->address : '') }}" placeholder="{{ trans('main.address') }}">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.address') }} 2 :</label>
                                        <input class="form-control" name="address2" value="{{ (!empty($data->paymentInfo) ? $data->paymentInfo->address2 : '') }}" placeholder="{{ trans('main.address') }} 2">
                                    </div> 
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.city') }} :</label>
                                        <input class="form-control" name="city" value="{{ (!empty($data->paymentInfo) ? $data->paymentInfo->city : '') }}" placeholder="{{ trans('main.city') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.region') }} :</label>
                                        <input class="form-control" name="region" value="{{ (!empty($data->paymentInfo) ? $data->paymentInfo->region : '') }}" placeholder="{{ trans('main.region') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.postal_code') }} :</label>
                                        <input class="form-control" name="postal_code" value="{{ (!empty($data->paymentInfo) ? $data->paymentInfo->postal_code : '') }}" placeholder="{{ trans('main.postal_code') }}">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.country') }} :</label>
                                        <input class="form-control" value="{{ (!empty($data->paymentInfo) ? $data->paymentInfo->country : '') }}" name="country" placeholder="{{ trans('main.country') }}">
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.paymentMethod') }} :</label>
                                        <select name="payment_method" class="form-control" data-toggle="select2">
                                            <option value="">{{ trans('main.choose') }}</option>
                                            <option value="1" {{ (!empty($data->paymentInfo) ? $data->paymentInfo->payment_method : '') == 1 ? 'selected' : '' }}>{{ trans('main.mada') }}</option>
                                            <option value="2" {{ (!empty($data->paymentInfo) ? $data->paymentInfo->payment_method : '') == 2 ? 'selected' : '' }}>{{ trans('main.visaMaster') }}</option>
                                            <option value="3" {{ (!empty($data->paymentInfo) ? $data->paymentInfo->payment_method : '') == 3 ? 'selected' : '' }}>{{ trans('main.bankTransfer') }}</option>
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.currency') }} :</label>
                                        <select name="currency" class="form-control" data-toggle="select2">
                                            <option value="">{{ trans('main.choose') }}</option>
                                            <option value="1" {{ (!empty($data->paymentInfo) ? $data->paymentInfo->currency : '') == 1 ? 'selected' : '' }}>{{ trans('main.sar') }}</option>
                                            <option value="2" {{ (!empty($data->paymentInfo) ? $data->paymentInfo->currency : '') == 2 ? 'selected' : '' }}>{{ trans('main.usd') }}</option>
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>{{ trans('main.tax_id') }} :</label>
                                        <input class="form-control" name="tax_id" value="{{ (!empty($data->paymentInfo) ? $data->paymentInfo->tax_id : '') }}" placeholder="{{ trans('main.tax_id') }}">
                                    </div> 
                                </div>
                            </div>

                            <button class="btn btn-success float-right my-2">{{ trans('main.edit') }}</button>
                            <div class="clearfix"></div>
                        </form>
                    </div> <!-- end tab-pane -->
                    <!-- end about me section content -->

                    <div class="tab-pane" id="timeline">
                        <h5 class="mb-4 text-uppercase"><i class="fas fa-cogs mr-1"></i>{{ trans('main.settings') }}</h5>
                        <form action="{{ URL::to('/clients/view/'.$data->data->id.'/updateSettings') }}" method="post" accept-charset="utf-8">
                        @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.pinCode') }} :</label>
                                        <input class="form-control" name="pin_code" value="{{ $data->data->pin_code }}" placeholder="{{ trans('main.pinCode') }}">
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname">{{ trans('main.emergencyNumber') }}</label>
                                        <input type="hidden" name="emergency_number">
                                        <input type="tel" name="emergency_tel" value="{{ $data->data->emergency_number }}" class="form-control teles">
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.status') }} :</label>
                                        <select name="status" class="form-control" data-toggle="select2">
                                            <option value="">{{ trans('main.choose') }}</option>
                                            <option value="0" {{ $data->data->status == 0 ? 'selected' : '' }}>{{ trans('main.notActive') }}</option>
                                            <option value="1" {{ $data->data->status == 1 ? 'selected' : '' }}>{{ trans('main.active') }}</option>
                                        </select>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ trans('main.twoAuthFactor') }} :</label>
                                        <select name="two_auth" class="form-control" data-toggle="select2">
                                            <option value="">{{ trans('main.choose') }}</option>
                                            <option value="0" {{ $data->data->two_auth == 0 ? 'selected' : '' }}>{{ trans('main.no') }}</option>
                                            <option value="1" {{ $data->data->two_auth == 1 ? 'selected' : '' }}>{{ trans('main.yes') }}</option>
                                        </select>
                                    </div> 
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-xs-12 col-md-6 border-0">
                                            <div class="checkbox checkbox-blue checkbox-single float-left mx-1">
                                                <input type="checkbox" name="offers" {{$data->data->offers == 1 ? 'checked' : ''}}>
                                                <label></label>
                                            </div>
                                            <p>{{ trans('main.offers') }}</p>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <div class="col-xs-12 col-md-6 border-0">
                                            <div class="checkbox checkbox-blue checkbox-single float-left mx-1">
                                                <input type="checkbox" name="notifications" {{$data->data->notifications == 1 ? 'checked' : ''}}>
                                                <label></label>
                                            </div>
                                            <p>{{ trans('main.notifications') }}</p>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <button class="btn btn-success float-right my-2 updateSettings">{{ trans('main.edit') }}</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>

                    <div class="tab-pane" id="channel_settings">
                        <h5 class="mb-4 text-uppercase"><i class="fas fa-cogs mr-1"></i>{{ trans('main.channel_settings') }}</h5>
                        <form action="{{ URL::to('/clients/view/'.$data->data->id.'/updateChannelSettings') }}" method="post" accept-charset="utf-8">
                            @csrf
                            <div class="form-group row mb-2">
                                <label class="col-4 col-form-label">{{trans('main.messageNotifications')}}</label>
                                <div class="col-8">
                                    <input type="text" dir="ltr" class="form-control" name="messageNotifications" placeholder="{{trans('main.messageNotifications')}}" value="{{!empty($data->channelSettings) ? @$data->channelSettings->webhooks['messageNotifications'] : ''}}">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-4 col-form-label">{{trans('main.ackNotifications')}}</label>
                                <div class="col-8">
                                    <input type="text" dir="ltr" class="form-control" name="ackNotifications" placeholder="{{trans('main.ackNotifications')}}" value="{{!empty($data->channelSettings) ? @$data->channelSettings->webhooks['ackNotifications'] : ''}}">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-4 col-form-label">{{trans('main.chatNotifications')}}</label>
                                <div class="col-8">
                                    <input type="text" dir="ltr" class="form-control" name="chatNotifications" placeholder="{{trans('main.chatNotifications')}}" value="{{!empty($data->channelSettings) ? @$data->channelSettings->webhooks['chatNotifications'] : ''}}">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label class="col-4 col-form-label">{{trans('main.businessNotifications')}}</label>
                                <div class="col-8">
                                    <input type="text" dir="ltr" class="form-control" name="businessNotifications" placeholder="{{trans('main.businessNotifications')}}" value="{{!empty($data->channelSettings) ? @$data->channelSettings->webhooks['businessNotifications'] : ''}}">
                                </div>
                            </div>
                            <button class="btn btn-success float-right my-2">{{ trans('main.save'). ' '.trans('main.channel_settings') }}</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                    <!-- end timeline content-->

                    <div class="tab-pane tickets" id="tickets">
                        <h5 class="mb-4 text-uppercase"><i class="fas fa-file-invoice mr-1"></i> {{ trans('main.tickets') }}</h5>
                        @if(!empty($data->tickets))
                        <!-- start user projects -->
                        <table class="data table table-striped no-margin">
                            <thead>
                                <tr>
                                    <th>{{ trans('main.id') }}</th>
                                    <th>{{ trans('main.department') }}</th>
                                    <th>{{ trans('main.subject') }}</th>
                                    <th>{{ trans('main.client') }}</th>
                                    <th>{{ trans('main.priority') }}</th>
                                    <th>{{ trans('main.date') }}</th>
                                    <th>{{ trans('main.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->tickets as $key => $value)
                                <tr>
                                    <td width="3%">{{ $value->id }}</td>
                                    <td>{{ $value->department }}</td>
                                    <td>{{ $value->subject }}</td>
                                    <td>{{ $value->client }}</td>
                                    <td>{{ $value->priority }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td width="150px" align="center">
                                        @if(\Helper::checkRules('edit-ticket'))
                                            <a href="{{ URL::to('/tickets/edit/' . $value->id) }}" class="btn btn-dark btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.edit')}}"><i class="fa fa-pencil-alt"></i></a>
                                        @endif
                                        @if(\Helper::checkRules('view-ticket'))
                                            <a href="{{ URL::to('/tickets/view/' . $value->id) }}" class="btn btn-dark btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.view')}}"><i class="fa fa-eye"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- end user projects -->
                        @else
                        <div class="empty">{{ trans('main.noTickets') }}</div>
                        @endif
                    </div>

                    <div class="tab-pane invoices" id="invoices">
                        <h5 class="mb-4 text-uppercase"><i class="fas fa-file-invoice mr-1"></i>{{ trans('main.invoices') }}</h5>
                        @if(!empty($data->invoices))
                        <table class="data table table-striped no-margin">
                            <thead>
                                <tr>
                                    <th>{{ trans('main.id') }}</th>
                                    <th>{{ trans('main.client') }}</th>
                                    <th>{{ trans('main.due_date') }}</th>
                                    <th>{{ trans('main.total') }}</th>
                                    <th>{{ trans('main.status') }}</th>
                                    <th>{{ trans('main.created_at') }}</th>
                                    <th>{{ trans('main.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data->invoices as $key => $value)
                                <tr>
                                    <td width="3%">{{ $value->id }}</td>
                                    <td>{{ $value->client }}</td>
                                    <td>{{ $value->due_date }}</td>
                                    <td>{{ $value->total }} {{ trans('main.sar') }}</td>
                                    <td>{{ $value->statusText }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td width="150px" align="center">
                                        @if(\Helper::checkRules('view-invoice'))
                                            <a href="{{ URL::to('/invoices/view/' . $value->id) }}" class="btn btn-dark btn-xs" data-toggle="tooltip" data-original-title="{{trans('main.view')}}"><i class="fa fa-eye"></i></a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="empty">{{ trans('main.noTickets') }}</div>
                        @endif
                    </div>
                </div> <!-- end tab-content -->
            </div> <!-- end card-box-->
        </div> <!-- end col -->
    </div>  
</div>

@endsection

@section('modals')
@include('central.Partials.photoswipe_modal')
@include('central.Partials.transferDaysModal')
@include('tenant.Partials.screen_modal')

<div class="modal fade" id="compensationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('main.compensate') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <table class="table table-bordered">
                        <thead>
                            <th>{{trans('main.name')}}</th>
                            <th>{{trans('main.type')}}</th>
                            <th>{{trans('main.subscriptionPeriod')}}</th>
                            <th>{{trans('main.start_date')}}</th>
                            <th>{{trans('main.end_date')}}</th>
                        </thead>
                        <tbody>
                            <tr data-cols="{{$data->data->membership_id}}" data-type="1">
                                <td>{{$data->membership->title}}</td>
                                <td>{{trans('main.membership')}}</td>
                                <td>{{$data->data->duration_type == 1 ? trans('main.monthly') : trans('main.yearly')}}</td>
                                <td>
                                    <input class="form-control datepickerInput" value="{{ date('Y-m-d',strtotime(@$data->channel->start_date)) }}" name="start_date" placeholder="{{ trans('main.start_date') }}">
                                </td>
                                <td>
                                    <input class="form-control datepickerInput" value="{{ date('Y-m-d',strtotime(@$data->channel->end_date)) }}" name="end_date" placeholder="{{ trans('main.end_date') }}">
                                </td>
                            </tr>
                            @foreach($data->addonsData as $key => $addon)
                            <tr data-cols="{{$addon->Addon->id}}" data-type="2">
                                <td>{{ $addon->Addon->{'title_'.LANGUAGE_PREF} }}</td>
                                <td>{{trans('main.addon')}}</td>
                                <td>{{$addon->duration_type == 1 ? trans('main.monthly') : trans('main.yearly')}}</td>
                                <td>
                                    <input class="form-control datepickerInput" value="{{ date('Y-m-d',strtotime($addon->start_date)) }}" name="start_date" placeholder="{{ trans('main.start_date') }}">
                                </td>
                                <td>
                                    <input class="form-control datepickerInput" value="{{ date('Y-m-d',strtotime($addon->end_date)) }}" name="end_date" placeholder="{{ trans('main.end_date') }}">
                                </td>
                            </tr>
                            @endforeach
                            @foreach($data->extraQuotasData as $key => $extraQuota)
                            <tr data-cols="{{$extraQuota->ExtraQuota->id}}" data-type="3">
                                <td>{{ $extraQuota->ExtraQuota->title }}</td>
                                <td>{{trans('main.extra_quota')}}</td>
                                <td>{{$extraQuota->duration_type == 1 ? trans('main.monthly') : trans('main.yearly')}}</td>
                                <td>
                                    <input class="form-control datepickerInput" value="{{ date('Y-m-d',strtotime($extraQuota->start_date)) }}" name="start_date" placeholder="{{ trans('main.start_date') }}">
                                </td>
                                <td>
                                    <input class="form-control datepickerInput" value="{{ date('Y-m-d',strtotime($extraQuota->end_date)) }}" name="end_date" placeholder="{{ trans('main.end_date') }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <label class="col-3 col-form-label">{{ trans('main.days') }} :</label>
                    <div class="col-9">
                        
                    </div> --}}
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success">{{ trans('main.save') }}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('main.back') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addonsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('main.addons') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5">
                <div class="form-group row">
                    <label>{{trans('main.addon')}}</label>
                    <select name="addon_id" class="form-control" data-toggle="select2">
                        <option value="">{{trans('main.choose')}}</option>
                        @foreach($data->addons as $addon)
                        <option value="{{$addon->id}}" {{ in_array($addon->id,$data->userAddons) ? 'disabled' : '' }}>{{$addon->title}}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="form-group row">
                    <label>{{trans('main.duration')}}</label>
                    <select name="duration_type" class="form-control" data-toggle="select2">
                        <option value="">{{trans('main.choose')}}</option>
                        <option value="1">{{trans('main.monthly')}}</option>
                        <option value="2">{{trans('main.yearly')}}</option>
                        <option value="3">{{trans('main.demo')}}</option>
                    </select>
                </div> 
                <div class="form-group row">
                    <label>{{trans('main.start_date')}}</label>
                    <input class="form-control datepickerInput" name="start_date" placeholder="{{ trans('main.start_date') }}">
                </div> 
                <div class="form-group row">
                    <label>{{trans('main.end_date')}}</label>
                    <input class="form-control datepickerInput" name="end_date" placeholder="{{ trans('main.end_date') }}">
                </div> 
                <div class="form-group row">
                    <label>{{trans('main.status')}}</label>
                    <select name="status" class="form-control" data-toggle="select2">
                        <option value="">{{trans('main.choose')}}</option>
                        <option value="1">{{trans('main.active')}}</option>
                        <option value="2">{{trans('main.deactivated')}}</option>
                        <option value="3">{{trans('main.notActive')}}</option>
                    </select>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success addUserAddon">{{ trans('main.save') }}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('main.back') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="extraQuotasModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ trans('main.extraQuotas') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5">
                <div class="form-group row">
                    <label>{{trans('main.extraQuotas')}}</label>
                    <select name="extra_quota_id" class="form-control" data-toggle="select2">
                        <option value="">{{trans('main.choose')}}</option>
                        @foreach($data->extraQuotas as $extraQuota)
                        <option value="{{$extraQuota->id}}">{{$extraQuota->title}}</option>
                        @endforeach
                    </select>
                </div> 
                <div class="form-group row">
                    <label>{{trans('main.duration')}}</label>
                    <select name="duration_type" class="form-control" data-toggle="select2">
                        <option value="">{{trans('main.choose')}}</option>
                        <option value="1">{{trans('main.monthly')}}</option>
                        <option value="2">{{trans('main.yearly')}}</option>
                        <option value="3">{{trans('main.demo')}}</option>
                    </select>
                </div> 
                <div class="form-group row">
                    <label>{{trans('main.start_date')}}</label>
                    <input class="form-control datepickerInput" name="start_date" placeholder="{{ trans('main.start_date') }}">
                </div> 
                <div class="form-group row">
                    <label>{{trans('main.end_date')}}</label>
                    <input class="form-control datepickerInput" name="end_date" placeholder="{{ trans('main.end_date') }}">
                </div> 
                <div class="form-group row">
                    <label>{{trans('main.status')}}</label>
                    <select name="status" class="form-control" data-toggle="select2">
                        <option value="">{{trans('main.choose')}}</option>
                        <option value="1">{{trans('main.active')}}</option>
                        <option value="2">{{trans('main.deactivated')}}</option>
                        <option value="3">{{trans('main.notActive')}}</option>
                    </select>
                </div> 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success addUserExtraQuota">{{ trans('main.save') }}</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ trans('main.back') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="{{ asset('assets/dashboard/assets/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/assets/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/assets/components/myPhotoSwipe.js') }}"></script>      
<script src="{{ asset('assets/dashboard/assets/components/addClient.js') }}" type="text/javascript"></script>
@endsection