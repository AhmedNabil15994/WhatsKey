{{-- Extends layout --}}
@extends('central.Layouts.Dashboard.master')
@section('title',$data->designElems['mainData']['title'])

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <div class="row d-block w-100">
        <div class="card invoice pd-25">
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <p>
                            <span class="float-left">{{ trans('main.due_date') }} :</span>  
                            <span class="float-left text-muted">{{ date('M d, Y',strtotime($data->data->due_date)) }}</span>
                            <div class="clearfix"></div>
                        </p>
                        @if($data->data->status == 1)
                        <p>
                            <span class="float-left">{{ trans('main.paymentMethod') }} :</span>
                            <span class="float-left text-muted">{{ $data->data->payment_gateaway }}</span> 
                            <div class="clearfix"></div> 
                        </p>
                        @endif
                    </div>
                    <div class="col-6">
                        <div class="invoice-header d-block mb-5">
                            @php
                                $className = '';
                                if($data->data->status == 0){
                                    $className = 'secondary';
                                }else if($data->data->status == 1){
                                    $className = 'success';
                                }else if($data->data->status == 2){
                                    $className = 'danger';
                                }else if($data->data->status == 3){
                                    $className = 'primary';
                                }else if($data->data->status == 4){
                                    $className = 'info';
                                }else if($data->data->status == 5){
                                    $className = 'warning';
                                }
                            @endphp
                            <h1 class="invoice-title font-weight-bold text-uppercase mb-1">
                                {{ trans('main.invoice') }} #{{ $data->data->id + 10000 }}
                                <span class="badge badge-md badge-{{ $className }}" style="padding: 6px;">
                                    {{ trans('main.invoice_status_'.$data->data->status) }}
                                </span>
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <p class="tx-bold mt-4">{{ trans('main.createdFor') }}</p>
                        <p class="tx-font-14">
                            {{ $data->data->company }}<br>
                            {{ $data->data->client }}<br>
                            {{ isset($data->paymentInfo) && isset($data->paymentInfo->address) ? $data->paymentInfo->address : '' }}<br>
                            {{ (isset($data->paymentInfo) && isset($data->paymentInfo->city) ? $data->paymentInfo->city  : ''). ', ' . (isset($data->paymentInfo) && isset($data->paymentInfo->region) ? $data->paymentInfo->region  : ''). ', ' .( isset($data->paymentInfo) && isset($data->paymentInfo->postal_code) ? $data->paymentInfo->postal_code  : '') }}<br>
                            {{ isset($data->paymentInfo) && isset($data->paymentInfo->country) ? $data->paymentInfo->country  : '' }}<br>
                            @if(isset($data->paymentInfo) && isset($data->paymentInfo->tax_id) ? $data->paymentInfo->tax_id : '')
                            <span class="float-left mt-2 w-auto m{{ DIRECTION == 'ltr' ? 'r' : 'l' }}-2">{{ trans('main.tax_id') }} : </span>  
                            <span class="float-left text-muted mt-2"> {{ isset($data->paymentInfo) ? $data->paymentInfo->tax_id : '' }}</span>
                            <div class="clearfix"></div>
                            @endif
                        </p>
                    </div>
                    <div class="col-8">
                        <p class="tx-bold mt-4">{{ trans('main.appName') }}</p>
                        <p class="tx-font-14">
                            {{ $data->companyAddress->servers }}<br>
                            {{ $data->companyAddress->address }}<br>
                            {{ $data->companyAddress->region . ', ' . $data->companyAddress->postal_code  }}<br>
                            {{ $data->companyAddress->city }}<br>
                            {{ $data->companyAddress->country  }}<br>
                            <span class="float-left mt-2 w-auto m{{ DIRECTION == 'ltr' ? 'r' : 'l' }}-2">{{ trans('main.tax_id') }} : </span>  
                            <span class="float-left text-muted mt-2"> {{ $data->companyAddress->tax_id }}</span>
                            <div class="clearfix"></div>
                        </p>
                    </div>
                </div>

                <div class="row w-100 d-block">
                    <div class="col-12">
                        <p class="tx-bold mt-4">{{ trans('main.invoice_items') }}</p>
                        <div class="table-responsive">
                            <table class="table mt-4 table-centered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th colspan="3">{{ trans('main.item') }}</th>
                                        <th>{{ trans('main.quantity') }}</th>
                                        {{-- <th>{{ trans('main.start_date') }}</th> --}}
                                        {{-- <th>{{ trans('main.end_date') }}</th> --}}
                                        <th class="text-center">{{ trans('main.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $prices = 0; @endphp
                                    @foreach($data->data->items as $key => $item)
                                    @php
                                        $prices += $item['price']; 
                                    @endphp
                                    <tr class="mainRow">
                                        <td>{{ $key+1 }}</td>
                                        <td colspan="3">
                                            <p class="m-0 d-inline-block align-middle font-16">
                                                <a href="#" class="text-reset font-family-secondary">{{ $item['title'] }}</a><br>
                                                <small class="mr-2"><b>{{ trans('main.extra_type') }}:</b> {{ trans('main.'.$item['type']) }} </small>
                                            </p>
                                        </td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td class="text-center">
                                            @php 
                                            $total = $item['quantity'] * $item['price'];
                                            $tax=  \Helper::calcTax($total);
                                            @endphp
                                            {{ $total - $tax }} 
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
                                        <td colspan="5" class="font-size-h6 text-right">{{trans('main.userCredits')}}</td>
                                        <td class="font-weight-bolder font-size-h6 text-right"> <span class="userCredits">{{$userCredits}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="5" class="font-size-h6 text-right">{{trans('main.discount')}}</td>
                                        <td class="font-weight-bolder font-size-h6 text-right"> 
                                            <span class="discount">{{$discount}}</span> <sup>{{trans('main.sar2')}}</sup> 
                                            @if($data->data->coupon_code != null)
                                            <p class="mb-0">{{trans('main.coupon_code')}} : {{$data->data->coupon_code}}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="font-size-h6 text-right">{{trans('main.grandTotal')}}</td>
                                        <td class="font-weight-bolder font-size-h6 text-right"> <span class="grandTotal">{{$grandTotal}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="font-size-h6 text-right">{{trans('main.estimatedTax')}}</td>
                                        <td class="font-weight-bolder font-size-h6 text-right"> <span class="tax">{{$tax}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="font-weight-bolder font-size-h4 text-right">{{trans('main.subTotal')}}</td>
                                        <td class="font-weight-bolder font-size-h4 text-right"> 
                                            <span class="total">{{$grandTotal + $tax}}</span> <sup>{{trans('main.sar2')}}</sup> 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive -->
                    </div> <!-- end col -->
                </div>

                @if($data->data->transaction_id)
                <div class="row w-100 d-block">
                    <div class="col-12">
                        <p class="tx-bold mt-4">{{ trans('main.transactions') }}</p>
                        <div class="table-responsive">
                            <table class="table mt-4 table-centered transactions">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ trans('main.transaction_date') }}</th>
                                        <th>{{ trans('main.paymentGateaway') }}</th>
                                        <th>{{ trans('main.transaction_id') }}</th>
                                        <th>{{ trans('main.transaction_price') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $mainPrices = 0; @endphp
                                    @foreach($data->data->items as $key => $item)
                                    @php $mainPrices+=$item['price'] * $item['quantity'] @endphp
                                    @endforeach
                                    <tr class="mainRow">
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                            <p class="m-0 d-inline-block align-middle font-16">
                                                <a href="#" class="text-reset font-family-secondary">{{ $data->data->paid_date }}</a><br>
                                            </p>
                                        </td>
                                        <td>{{ $data->data->payment_gateaway }}</td>
                                        <td>{{ $data->data->transaction_id }}</td>
                                        <td>{{ $data->data->total }} {{ trans('main.sar') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive -->
                    </div> <!-- end col -->
                </div>
                @endif

                <div class="row text-right d-block">
                    <div class="mt-4 mb-1">
                        <div class="d-print-none">
                            <a href="{{ URL::to('/invoices/'.$data->data->id.'/downloadPDF') }}" class="btn btn-info waves-effect waves-light">PDF</a>

                            <a href="javascript:window.print()" class="btn btn-primary waves-effect waves-light"><i class="mdi mdi-printer mr-1"></i> {{ trans('main.print') }}</a>
                            <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-danger waves-effect waves-light">{{ trans('main.back') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
@endsection