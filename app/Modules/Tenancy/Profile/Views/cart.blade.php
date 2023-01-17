{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',trans('main.myCart'))
@section('pageName',trans('main.myCart'))

@section('styles')
<link rel="stylesheet" href="{{asset('assets/tenant/css/pages/wizard/wizard-1.css')}}">
<link href="{{asset('assets/tenant/plugins/custom/uppy/uppy.bundle.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumbs')
@include('tenant.Layouts.breadcrumb',[
    'breadcrumbs' => [
        [
            'title' => trans('main.menu'),
            'url' => \URL::to('/dashboard')
        ],
        [
            'title' => trans('main.myAccount'),
            'url' => \URL::to('/profile/subscription')
        ],
        [
            'title' => trans('main.myCart'),
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title">
            <span class="svg-icon svg-icon-xl svg-icon-primary">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                        <path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000"></path>
                    </g>
                </svg>
            </span>
            {{trans('main.myCart')}}
        </h3>
    </div>
    <div class="card-body">
        <div class="wizard wizard-1" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="false">
            <div class="wizard-nav border-bottom">
                <div class="wizard-steps p-8 p-lg-10">
                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                        <div class="wizard-label">
                            <span class="svg-icon svg-icon-3x wizard-icon svg-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                        <path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000"></path>
                                    </g>
                                </svg>
                            </span>
                            <h3 class="wizard-title">1. {{ trans('main.myCart') }}</h3>
                        </div>
                        <span class="svg-icon svg-icon-xl wizard-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                </g>
                            </svg>
                        </span>
                    </div>
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-label">
                            <i class="wizard-icon flaticon-info"></i>
                            <h3 class="wizard-title">2. {{ trans('main.payment_setting') }}</h3>
                        </div>
                        <span class="svg-icon svg-icon-xl wizard-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                </g>
                            </svg>
                        </span>
                    </div>
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-label">
                            <i class="wizard-icon flaticon-cogwheel-1"></i>
                            <h3 class="wizard-title">3. {{ trans('main.paymentMethod') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center my-10 px-8 my-lg-15 px-lg-10">
                <div class="col-xl-12">
                    <form class="form" id="kt_form" method="post" action="{{URL::to('/checkout')}}" accept-charset="utf-8" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{$data->invoice_id}}" name="invoice_id">
                        <div id="step1" class="active myStep" data-wizard-type="step-content" data-wizard-state="current">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{trans('main.item')}}</th>
                                            <th class="text-center">{{trans('main.itemType')}}</th>
                                            <th class="text-center">{{trans('main.quantity')}}</th>
                                            <th class="text-center">{{trans('main.date')}}</th>
                                            <th class="text-right">{{trans('main.total')}}</th>
                                            <th class="text-center">{{trans('main.actions')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $prices = 0;
                                            $discount = 0;
                                        @endphp
                                        @foreach($data->items as $oneItem)
                                        @php
                                            $url = $oneItem['type'] == 'membership' ? URL::to('/profile/subscription/memberships') : '';
                                            $prices += $oneItem['price']; 
                                        @endphp
                                        <tr>
                                            <td class="d-flex align-items-center font-weight-bolder">
                                                <div class="symbol symbol-60 flex-shrink-0 mr-4 bg-light">
                                                    <div class="symbol-label"></div>
                                                </div>
                                                <a href="#" class="text-dark text-hover-primary">{{$oneItem['title']}}</a>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="mr-2 font-weight-bolder">{{trans('main.'.$oneItem['type'])}}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="mr-2 font-weight-bolder">1</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="mr-2 font-weight-bolder">{{$oneItem['start_date'] . ' - ' . $oneItem['end_date']}}</span>
                                            </td>
                                            <td class="text-right align-middle font-weight-bolder font-size-h5">{{$oneItem['price']}} <sup>{{trans('main.sar2')}}</sup>  </td>
                                            <td class="text-right align-middle text-center">
                                                <a href="{{$url}}" class="btn btn-outline-danger btn-md btn-icon font-weight-bolder font-size-sm"><i class="la la-trash"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @php
                                            $userCredits = $data->userCredits;
                                            $tax = \Helper::calcTax($prices - $discount - $userCredits);
                                            $grandTotal = $prices - $userCredits - $discount - $tax;
                                        @endphp
                                        @if($userCredits > 0)
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="font-size-h6 text-right">{{trans('main.userCredits')}}</td>
                                            <td class="font-weight-bolder font-size-h6 text-right"> <span class="userCredits">{{$userCredits}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="font-size-h6 text-right">{{trans('main.discount')}}</td>
                                            <td class="font-weight-bolder font-size-h6 text-right"> <span class="discount">{{$discount}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="font-size-h6 text-right">{{trans('main.grandTotal')}}</td>
                                            <td class="font-weight-bolder font-size-h6 text-right"> <span class="grandTotal">{{$grandTotal}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="font-size-h6 text-right">{{trans('main.estimatedTax')}}</td>
                                            <td class="font-weight-bolder font-size-h6 text-right"> <span class="tax">{{$tax}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="font-weight-bolder font-size-h4 text-right">{{trans('main.subTotal')}}</td>
                                            <td class="font-weight-bolder font-size-h4 text-right"> <span class="total">{{$grandTotal + $tax}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                        </tr>
                                     
                                        <tr>
                                            <td colspan="6" class="border-0 text-muted text-right pt-0">{{trans('main.taxesIncluded')}}</td>
                                        </tr>
                                     
                                        <tr>
                                            <td colspan="4" class="border-0 pt-10 coupon">
                                                <div class="form-group row mb-0">
                                                    <div class="col-md-3 d-flex">
                                                        <label class="font-weight-bolder">{{trans('main.couponCode')}}</label>
                                                    </div>
                                                    <div class="col-md-7">
                                                        <div class="input-group w-100">
                                                            <input type="text" class="form-control" placeholder="{{trans('main.couponCode')}}" />
                                                            <div class="input-group-append">
                                                                <button class="btn btn-secondary addCoupon" type="button">{{trans('main.apply')}}</button>
                                                            </div>
                                                        </div>
                                                        <p class="border-0 mt-2 text-muted text-right pt-0">{{trans('main.taxesIncluded2')}}</p>
                                                    </div>                                
                                                </div>
                                            </td>
                                            <td colspan="2" class="border-0 text-right pt-10"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="step2" class="myStep" data-wizard-type="step-content">
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
                                    <label>{{ trans('main.tax_id') }} :</label>
                                    <input class="form-control" value="{{ $data->paymentInfo && isset($data->paymentInfo->tax_id) ? $data->paymentInfo->tax_id : '' }}" name="tax_id" placeholder="{{ trans('main.tax_id') }}">
                                </div> 
                            </div>
                        </div>
                        <div id="step3" class="myStep" data-wizard-type="step-content">
                            <div class="row mb-15">
                                <div class="col-md-6">
                                    <div class="paymentStyle" data-area="2">
                                        <h2 class="titleSelect">{{ trans('main.ePayment') }}</h2>
                                        <div class="row clearfix my-35 mx-0">
                                            <div class="col-4">
                                                <a href="{{URL::to('/profile/subscription/activate?invoice_id='.$data->invoice_id)}}"><img src="{{ asset('assets/tenant/images/payment1.png') }}" alt="mada" /></a>
                                            </div>
                                            <div class="col-4">
                                                <a href="{{URL::to('/profile/subscription/activate?invoice_id='.$data->invoice_id)}}"><img src="{{ asset('assets/tenant/images/payment2.png') }}" alt="visa" /></a>
                                            </div>
                                            <div class="col-4">
                                                <a href="{{URL::to('/profile/subscription/activate?invoice_id='.$data->invoice_id)}}"><img src="{{ asset('assets/tenant/images/payment3.png') }}" alt="mastercard" /></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="paymentStyle" data-area="1">
                                        <h2 class="titleSelect mb-5">{{ trans('main.bankTransfer') }}</h2>
                                        <input type="file" class="hidden" name="transfer_image">
                                        <div class="uppy" id="kt_uppy_3">
                                            <div class="uppy-drag"></div>
                                            <div class="uppy-informer"></div>
                                            <div class="uppy-progress"></div>
                                            <div class="uppy-thumbnails"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-10">
                            <div class="mr-2">
                                <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4 btnPrev dis" data-wizard-type="action-prev">{{trans('main.prev')}}</button>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4 finish" data-wizard-type="action-submit">{{trans('main.finish')}}</button>
                                <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 btnNext" data-wizard-type="action-next">{{trans('main.next')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="{{asset('assets/tenant/plugins/custom/uppy/uppy.bundle.js')}}"></script>
<script src="{{ asset('assets/tenant/components/cart.js') }}" type="text/javascript"></script>
@endsection
