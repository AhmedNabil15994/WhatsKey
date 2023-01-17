@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="print">
    #kt_header,.subheader,#kt_header_mobile,.alert,.inv-footer,.footer,#kt_scrolltop{
        display: none !important;
    }
    .header-fixed.subheader-fixed.subheader-enabled .wrapper{
        padding-top: 0;
        padding-bottom: 0;
        background-color: #FFF;
    }
    .content{
        padding-bottom: 0;
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
            'title' => trans('main.invoices'),
            'url' => \URL::to('/invoices')
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection
@section('content')
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="row justify-content-center pt-8 px-8 pt-md-27 px-md-0">
            <div class="col-md-9">
                <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
                    <h1 class="display-4 font-weight-boldest mb-10">{{trans('main.invoice')}}</h1>
                    <div class="d-flex flex-column align-items-md-end px-0">
                        <a href="#" class="mb-5 max-w-200px">
                            <span class="svg-icon svg-icon-full">
                                <img src="{{asset('assets/images/whiteLogo.png')}}" alt="">
                            </span>
                        </a>
                        <span class="d-flex flex-column align-items-md-end font-size-h5 font-weight-bold text-muted">
                            <span>{{ $data->companyAddress->servers }}</span> 
                            <span>{{ $data->companyAddress->address }}</span>
                            <span>{{ $data->companyAddress->region . ', ' . $data->companyAddress->postal_code  }}</span>
                            <span>{{ $data->companyAddress->country  }}</span>
                            <span>{{ $data->companyAddress->tax_id }}</span>
                        </span>
                    </div>
                </div>
                <div class="rounded-xl overflow-hidden w-100 max-h-md-250px mb-30">
                    <img src="{{asset('assets/tenant/media/bg/bg-invoice-5.jpg')}}" class="w-100" alt="">
                </div>
                <!--begin: Invoice body-->
                <div class="row border-bottom pb-10">
                    <div class="col-md-9 py-md-10 pr-md-10">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="pt-1 pb-9 pl-0 font-weight-bolder text-muted font-size-lg text-uppercase">#</th>
                                        <th class="pt-1 pb-9 text-right font-weight-bolder text-muted font-size-lg text-uppercase">{{trans('main.item')}}</th>
                                        <th class="pt-1 pb-9 text-right font-weight-bolder text-muted font-size-lg text-uppercase">{{trans('main.quantity')}}</th>
                                        <th class="pt-1 pb-9 text-right pr-0 font-weight-bolder text-muted font-size-lg text-uppercase">{{trans('main.total')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $prices = 0; @endphp
                                    @foreach($data->data->items as $key => $item)
                                    @php
                                        $prices += $item['price']; 
                                    @endphp
                                    <tr class="font-weight-bolder font-size-lg">
                                        <td class="border-top-0 pl-0 pt-7 d-flex align-items-center">{{ $key+1 }}</td>
                                        <td class="text-right pt-7">
                                            {{ $item['title'] }} <br>
                                            <small><b>{{ trans('main.extra_type') }}:</b> {{ trans('main.'.$item['type']) }} </small>
                                        </td>
                                        <td class="text-right pt-7">{{ $item['quantity'] }}</td>
                                        <td class="pr-0 pt-7 font-size-h6 font-weight-boldest text-right">
                                            @php 
                                            $total = $item['quantity'] * $item['price'];
                                            $tax=  \Helper::calcTax($total);
                                            @endphp
                                            {{ $total }} 
                                            {{ trans('main.sar') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @php
                                        $userCredits = $data->data->user_credits;
                                        $discount = $data->data->coupon_code != null ? ($data->data->discount_type == 1 ? $data->data->discount_value : round($prices-$userCredits * $data->data->discount_value / 100 ,2)) : 0;
                                        $tax = \Helper::calcTax($prices - $discount - $userCredits);
                                        $grandTotal = $prices - $userCredits - $discount - $tax;
                                    @endphp
                                    @if($userCredits > 0)
                                    <tr>
                                        <td colspan="2" class="font-size-h6 text-right">{{trans('main.userCredits')}}</td>
                                        <td colspan="2" class="font-weight-bolder font-size-h6 text-right"> <span class="userCredits">{{$userCredits}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" class="font-size-h6 text-right">{{trans('main.discount')}}</td>
                                        <td colspan="2" class="font-weight-bolder font-size-h6 text-right"> 
                                            <span class="discount">{{$discount}}</span> <sup>{{trans('main.sar2')}}</sup> 
                                            @if($data->data->coupon_code != null)
                                            <p class="mb-0">{{trans('main.coupon_code')}} : {{$data->data->coupon_code}}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="font-size-h6 text-right">{{trans('main.grandTotal')}}</td>
                                        <td colspan="2" class="font-weight-bolder font-size-h6 text-right"> <span class="grandTotal">{{$grandTotal}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="font-size-h6 text-right">{{trans('main.estimatedTax')}}</td>
                                        <td colspan="2" class="font-weight-bolder font-size-h6 text-right"> <span class="tax">{{$tax}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="font-weight-bolder font-size-h4 text-right">{{trans('main.subTotal')}}</td>
                                        <td colspan="2" class="font-weight-bolder font-size-h4 text-right"> 
                                            <span class="total">{{$grandTotal + $tax}}</span> <sup>{{trans('main.sar2')}}</sup> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="border-bottom w-100 mt-0 mb-13"></div>
                        @if($data->data->transaction_id)
                        <div class="d-flex flex-column flex-md-row">
                            <div class="d-flex flex-column mb-10 mb-md-0">
                                <div class="font-weight-bold font-size-h6 mb-3">{{trans('main.transactions')}}</div>
                                <div class="d-flex justify-content-between font-size-lg mb-3">
                                    <span class="font-weight-bold mr-15">{{ trans('main.transaction_date') }}:</span>
                                    <span class="text-right">{{ $data->data->paid_date }}</span>
                                </div>
                                <div class="d-flex justify-content-between font-size-lg mb-3">
                                    <span class="font-weight-bold mr-15">{{ trans('main.paymentGateaway') }}:</span>
                                    <span class="text-right">{{ $data->data->payment_gateaway }}</span>
                                </div>
                                <div class="d-flex justify-content-between font-size-lg mb-3">
                                    <span class="font-weight-bold mr-15">{{ trans('main.transaction_id') }}:</span>
                                    <span class="text-right">{{ $data->data->transaction_id }}</span>
                                </div>
                                <div class="d-flex justify-content-between font-size-lg">
                                    <span class="font-weight-bold mr-15">{{ trans('main.transaction_price') }}:</span>
                                    <span class="text-right">{{ $data->data->total . ' ' .trans('main.sar') }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-3 border-left-md pl-md-10 py-md-10 text-right">
                        <div class="font-size-h4 font-weight-bolder text-muted mb-3">{{ trans('main.total') }}</div>
                        <div class="font-size-h4 font-weight-boldest mb-3">{{ $data->data->total }}  {{ trans('main.sar') }}</div>
                        <div class="text-muted font-weight-bold mb-16">{{trans('main.taxesIncluded')}}</div>
                        <div class="border-bottom w-100 mb-16"></div>
                        <div class="text-dark-50 font-size-lg font-weight-bold mb-3">{{ trans('main.createdFor') }}.</div>
                        <div class="font-size-lg font-weight-bold mb-10">
                            {{ $data->data->company }}
                            <br>
                            {{ $data->data->client }}
                            <br>
                            {{ (isset($paymentObj) ? $paymentObj->address : '') }}
                            <br>
                            {{ (isset($paymentObj) ? $paymentObj->city : '') . ', ' . (isset($paymentObj) ? $paymentObj->region : '') . ', ' . (isset($paymentObj) ? $paymentObj->postal_code : '')  }}
                            <br>
                            {{ (isset($paymentObj) ? $paymentObj->country : '')  }}
                            <br>
                            @if((isset($paymentObj) ? $paymentObj->tax_id : ''))
                            {{ trans('main.tax_id') }}: {{ $paymentObj->tax_id }}
                            @endif
                        </div>
                        <!--end::Invoice To-->
                        <!--begin::Invoice No-->
                        <div class="text-dark-50 font-size-lg font-weight-bold mb-3">{{trans('main.invoiceId')}}.</div>
                        <div class="font-size-lg font-weight-bold mb-10">{{trans('main.invoice') . ' #'. ($data->data->id + 10000)}}</div>
                        <!--end::Invoice No-->
                        <!--begin::Invoice Date-->
                        <div class="text-dark-50 font-size-lg font-weight-bold mb-3">{{ trans('main.pubDate') }}</div>
                        <div class="font-size-lg font-weight-bold mb-10">{{ date('M d, Y',strtotime($data->data->created_at)) }}</div>
                        <!--end::Invoice Date-->
                        <div class="text-dark-50 font-size-lg font-weight-bold mb-3">{{ trans('main.eInvoice') }}.</div>
                        <div class="font-size-lg font-weight-bold mb-3">
                            <img src="{{$data->qrImage}}" width="100" height="100">
                        </div>
                    </div>
                </div>
                <!--end: Invoice body-->
            </div>
        </div>
        <!-- begin: Invoice action-->
        <div class="row justify-content-center inv-footer py-8 px-8 py-md-28 px-md-0">
            <div class="col-md-9">
                <div class="d-block font-size-sm flex-wrap">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-primary font-weight-bolder mr-3 my-1" onclick="window.print();">{{ trans('main.print') }}</button>
                            <a href="{{ URL::to('/invoices/'.$data->data->id.'/downloadPDF') }}" target="_blank" class="btn btn-light-primary font-weight-bolder mr-3 my-1">PDF</a>
                        </div>
                        <div class="col-md-6 text-right">
                            @if(\Helper::checkRules('pay-invoice') && $data->data->status != 1)
                            <a href="{{ URL::current().'/checkout' }}" class="btn btn-dark font-weight-bolder ml-sm-auto mr-3 my-1">{{ trans('main.checkout') }}</a>
                            @endif
                            <a href="{{ URL::to('/invoices') }}" class="btn btn-secondary font-weight-bolder ml-sm-auto my-1">{{ trans('main.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: Invoice action-->
        <!--end::Invoice-->
    </div>
</div>
@endsection
@section('scripts')

@endsection
