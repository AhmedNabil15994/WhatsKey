{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="screen">
    .form .btnsTabs li{
        width: 200px;
    }
    .form{
        overflow: unset;
    }
    .form textarea{
        height: 250px;
    }
    .form p.data{
        display: inherit;
    }
    .col-xs-12.text-right.actions .nextPrev{
        padding: 10px 30px 30px 30px;
    }
    .form .content{
        padding-bottom: 0;
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/tenant/css/photoswipe.css') }}" />
<style>
    .form-group.textWrap emoji-picker{
        top: 40px;
    }
    html[dir="ltr"] .form-group.textWrap emoji-picker{
        right: 30px;
    }
    html[dir="rtl"] .form-group.textWrap emoji-picker{
        left: 30px;
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
<div class="card card-custom formNumbers">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <div class="card-body">
        <div class="example-preview">
            <ul class="nav nav-light-success nav-pills btnsTabs contacts" id="myTab3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="info-tab-3" data-toggle="tab" href="#info-3">
                        <span class="nav-icon">
                            <i class="la la-user-edit icon-2x"></i>
                        </span>
                        <span class="nav-text">{{ trans('main.account_setting') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="password-tab-3" data-toggle="tab" href="#password-3" aria-controls="password">
                        <span class="nav-icon">
                            <i class="la la-user-shield icon-2x"></i>
                        </span>
                        <span class="nav-text">{{ trans('main.changePassword') }}</span>
                    </a>
                </li>
                @if(\Helper::checkRules('paymentInfo,taxInfo'))
                <li class="nav-item">
                    <a class="nav-link" id="payment-tab-3" data-toggle="tab" href="#payment-3" aria-controls="payment">
                        <span class="nav-icon">
                            <i class="la la-user-cog icon-2x"></i>
                        </span>
                        <span class="nav-text">{{ trans('main.payment_setting') }}</span>
                    </a>
                </li>
                @endif
            </ul>
            <div class="tab-content mt-5" id="myTabContent3">
                <div class="tab-pane fade active show" id="info-3" role="tabpanel" aria-labelledby="info-tab-3">
                    <form class="form-horizontal grpmsg" method="POST" action="{{ URL::to('/profile/updatePersonalInfo') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-3 textWrap">
                                <label>{{ trans('main.name2') }} :</label>
                                <input class="form-control" name="name" value="{{ $data->data->name }}" placeholder="{{ trans('main.name2') }}">
                                <i class="la la-smile icon-xl emoji-icon"></i>
                                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                            </div> 
                            <div class="form-group mb-3 textWrap">
                                <label>{{ trans('main.company_name') }} :</label>
                                <input class="form-control" name="company" value="{{ $data->data->company }}" placeholder="{{ trans('main.company_name') }}">
                                <i class="la la-smile icon-xl emoji-icon"></i>
                                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.email') }} :</label>
                                <input class="form-control" type="email" value="{{ $data->data->email }}" name="email" placeholder="{{ trans('main.email') }}">
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.phone') }} :</label>
                                <input type="hidden" name="phone">
                                <input class="form-control" id="telephone" dir="ltr" type="tel" value="{{ '+'.$data->data->phone }}" name="phone" placeholder="{{ trans('main.phone') }}">
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.domain') }} :</label>
                                <input class="form-control" type="text" value="{{ $data->data->domain }}" name="domain" placeholder="{{ trans('main.domain') }}">
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.emergencyNumber') }}</label>
                                <input class="form-control" type="tel" name="emergency_number" value="{{ $data->data->emergency_number }}" class=" emergency_number" dir="ltr">
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ trans('main.twoAuthFactor') }} :</label>
                                <select class="form-control" name="two_auth" data-toggle="select2">
                                    <option value="">{{ trans('main.choose') }}</option>
                                    <option value="0" {{ $data->data->two_auth == 0 ? 'selected' : '' }}>{{ trans('main.no') }}</option>
                                    <option value="1" {{ $data->data->two_auth == 1 ? 'selected' : '' }}>{{ trans('main.yes') }}</option>
                                </select>
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.image') }} :</label>
                                <div class="dropzone" id="kt_dropzone_11">
                                    <div class="fallback">
                                        <input name="file" type="file" />
                                    </div>
                                    <div class="dz-message needsclick">
                                        <i class="h1 si si-cloud-upload"></i>
                                        <h3>{{ trans('main.dropzoneP') }}</h3>
                                    </div>
                                    @if($data->data->photo != '')
                                    <div class="dz-preview dz-image-preview" id="my-preview">  
                                        <div class="dz-image">
                                            <img alt="image" src="{{ $data->data->photo }}">
                                        </div>  
                                        <div class="dz-details">
                                            <div class="dz-size">
                                                <span><strong>{{ $data->data->photo_size }}</strong></span>
                                            </div>
                                            <div class="dz-filename">
                                                <span data-dz-name="">{{ $data->data->photo_name }}</span>
                                            </div>
                                            <div class="PhotoBTNS">
                                                <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                                   <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                                        <a href="{{ $data->data->photo }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                                        <img src="{{ $data->data->photo }}" itemprop="thumbnail" style="display: none;">
                                                    </figure>
                                                </div>
                                                <a class="DeletePhoto" data-area="{{ $data->data->id }}"><i class="fa fa-trash" data-name="{{ $data->data->photo_name }}" data-clname="Photo"></i> </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary mr-2">{{trans('main.edit')}}</button>
                                <a href="{{ URL::to('/dashboard') }}" class="btn btn-secondary">{{trans('main.back')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="password-3" role="tabpanel" aria-labelledby="password-tab-3">
                    <form class="form-horizontal grpmsg" method="POST" action="{{ URL::to('/profile/postChangePassword') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label>{{ trans('auth.password') }}</label>
                                <input class="form-control" type="password" name="password" placeholder="{{ trans('auth.passwordPlaceHolder') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ trans('auth.passwordConf') }}</label>
                                <input class="form-control" type="password" name="password_confirmation" placeholder="{{ trans('auth.passwordConfPlaceHolder') }}">
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary mr-2">{{trans('main.edit')}}</button>
                                <a href="{{ URL::to('/dashboard') }}" class="btn btn-secondary">{{trans('main.back')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
                @if(\Helper::checkRules('paymentInfo,taxInfo'))
                <div class="tab-pane fade" id="payment-3" role="tabpanel" aria-labelledby="payment-tab-3">
                    <form class="form-horizontal grpmsg" method="POST" action="{{ URL::to('/profile/postPaymentInfo') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label>{{ trans('main.country') }} :</label>
                                <input class="form-control" value="{{ $data->paymentInfo && isset($data->paymentInfo->country) ? $data->paymentInfo->country : '' }}" name="country" placeholder="{{ trans('main.country') }}">
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.region') }} :</label>
                                <input class="form-control" name="region" value="{{ $data->paymentInfo && isset($data->paymentInfo->region) ? $data->paymentInfo->region : '' }}" placeholder="{{ trans('main.region') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ trans('main.city') }} :</label>
                                <input class="form-control" name="city" value="{{ $data->paymentInfo && isset($data->paymentInfo->city) ? $data->paymentInfo->city : '' }}" placeholder="{{ trans('main.city') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label>{{ trans('main.address') }} :</label>
                                <input class="form-control" name="address" value="{{ $data->paymentInfo && isset($data->paymentInfo->address) ? $data->paymentInfo->address : '' }}" placeholder="{{ trans('main.address') }}">
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.address') }} 2 :</label>
                                <input class="form-control" name="address2" value="{{ $data->paymentInfo && isset($data->paymentInfo->address2) ? $data->paymentInfo->address2 : '' }}" placeholder="{{ trans('main.address') }} 2">
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.postal_code') }} :</label>
                                <input class="form-control" name="postal_code" value="{{ $data->paymentInfo && isset($data->paymentInfo->postal_code) ? $data->paymentInfo->postal_code : '' }}" placeholder="{{ trans('main.postal_code') }}">
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.paymentMethod') }} :</label>
                                <select class="form-control" name="payment_method" data-toggle="select2">
                                    <option value="">{{ trans('main.choose') }}</option>
                                    <option value="1" {{ $data->paymentInfo && isset($data->paymentInfo->payment_method) && $data->paymentInfo->payment_method == 1 ? 'selected' : '' }}>{{ trans('main.mada') }}</option>
                                    <option value="2" {{ $data->paymentInfo && isset($data->paymentInfo->payment_method) && $data->paymentInfo->payment_method == 2 ? 'selected' : '' }}>{{ trans('main.visaMaster') }}</option>
                                    <option value="3" {{ $data->paymentInfo && isset($data->paymentInfo->payment_method) && $data->paymentInfo->payment_method == 3 ? 'selected' : '' }}>{{ trans('main.bankTransfer') }}</option>
                                </select>
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.currency') }} :</label>
                                <select class="form-control" name="currency" data-toggle="select2">
                                    <option value="">{{ trans('main.choose') }}</option>
                                    <option value="1" {{ $data->paymentInfo && isset($data->paymentInfo->currency) && $data->paymentInfo->currency == 1 ? 'selected' : '' }}>{{ trans('main.sar') }}</option>
                                    <option value="2" {{ $data->paymentInfo && isset($data->paymentInfo->currency) && $data->paymentInfo->currency == 2 ? 'selected' : '' }}>{{ trans('main.usd') }}</option>
                                </select>
                            </div> 
                            <div class="form-group mb-3">
                                <label>{{ trans('main.tax_id') }} :</label>
                                <input class="form-control" value="{{ $data->paymentInfo && isset($data->paymentInfo->tax_id) ? $data->paymentInfo->tax_id : '' }}" name="tax_id" placeholder="{{ trans('main.tax_id') }}">
                            </div> 
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary mr-2">{{trans('main.edit')}}</button>
                                <a href="{{ URL::to('/dashboard') }}" class="btn btn-secondary">{{trans('main.back')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
@include('tenant.Partials.photoswipe_modal')
@endsection


@section('scripts')
<script src="{{ asset('assets/tenant/components/profile.js') }}"></script>
<script src="{{ asset('assets/tenant/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/tenant/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/tenant/components/myPhotoSwipe.js') }}"></script>      
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection
