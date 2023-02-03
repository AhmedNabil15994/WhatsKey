<!DOCTYPE html>
<html lang="{{ LANGUAGE_PREF }}" dir="{{ DIRECTION }}">
	<head>
		<meta charset="utf-8" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, height=device-height ,  maximum-scale=1 , initial-scale=1">
		<title>واتس كي | WhatsKey | @yield('title')</title>
		<meta name="description" content="#" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="{{ !isset($data->fontFile) ? config('app.BASE_URL').'/assets/tenant/css/font.css' : $data->fontFile }}" />
		{{-- <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet"> --}}
		<style type="text/css">
			html,body{
				font-family: "Tajawal-Regular" !important;
			}
			.card {
			    position: relative;
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -webkit-box-orient: vertical;
			    -webkit-box-direction: normal;
			    -ms-flex-direction: column;
			    flex-direction: column;
			    min-width: 0;
			    word-wrap: break-word;
			    background-color: #fff;
			    background-clip: border-box;
			    border: 1px solid #ebedf3;
			    border-radius: 0.42rem;
			}
			.card.card-custom {
			    -webkit-box-shadow: 0 0 30px 0 rgb(82 63 105 / 5%);
			    box-shadow: 0 0 30px 0 rgb(82 63 105 / 5%);
			    border: 0;
			}
			.card.card-custom>.card-body {
			    padding: 2rem 2.25rem;
			}
			.p-0 {
			    padding: 0!important;
			}
			.card-body {
			    -webkit-box-flex: 1;
			    -ms-flex: 1 1 auto;
			    flex: 1 1 auto;
			    min-height: 1px;
			    padding: 2.25rem;
			}
			.pl-8, .px-8 {
			    padding-right: 2rem!important;
			}
			.pr-8, .px-8 {
			    padding-left: 2rem!important;
			}
			.pt-8, .py-8 {
			    padding-top: 2rem!important;
			}
			.justify-content-center {
			    -webkit-box-pack: center!important;
			    -ms-flex-pack: center!important;
			    justify-content: center!important;
			}
			.row {
			    display: -webkit-box;
			    display: -ms-flexbox;
			    display: flex;
			    -ms-flex-wrap: wrap;
			    flex-wrap: wrap;
			    margin-left: -12.5px;
			    margin-right: -12.5px;
			}
			.col-md-12 {
			    -webkit-box-flex: 0;
			    -ms-flex: 0 0 100%;
			    flex: 0 0 100%;
			    max-width: 100%;
			}
			.col-md-9 {
			    width: 100%;
			    display:inline-block;
			}
			.justify-content-between {
			    -webkit-box-pack: justify!important;
			    -ms-flex-pack: justify!important;
			    justify-content: space-between!important;
			}
			.flex-column {
			    -webkit-box-orient: vertical!important;
			    -webkit-box-direction: normal!important;
			    -ms-flex-direction: column!important;
			    flex-direction: column!important;
			}
			.d-flex {
			    display: -webkit-box!important;
			    display: -ms-flexbox!important;
			    display: flex!important;
			}
			.pb-10, .py-10 {
			    padding-bottom: 2.5rem!important;
			}
			.display-4 {
			    font-size: 2.5rem!important;
			}
			.font-weight-boldest {
			    font-weight: 700;
			}
			.mb-10, .my-10 {
			    margin-bottom: 2.5rem!important;
			}
			.display-4 {
			    font-size: 2.5rem;
			    font-weight: 300;
			    line-height: 1.2;
			}
			.h1, h1 {
			    font-size: 2rem;
			}
			.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
			    margin-bottom: 0.5rem;
			    font-weight: 500;
			    line-height: 1.2;
			}
			h1, h2, h3, h4, h5, h6 {
			    margin-top: 0;
			    margin-bottom: 0.5rem;
			}
			.max-w-200px {
			    max-width: 200px!important;
			}
			.mb-5, .my-5 {
			    margin-bottom: 1.25rem!important;
			}
			a {
			    color: inherit;
			}
			a, button {
			    outline: 0!important;
			}
			a {
			    -webkit-transition: color .15s ease,background-color .15s ease,border-color .15s ease,-webkit-box-shadow .15s ease;
			    transition: color .15s ease,background-color .15s ease,border-color .15s ease,-webkit-box-shadow .15s ease;
			    transition: color .15s ease,background-color .15s ease,border-color .15s ease,box-shadow .15s ease;
			    transition: color .15s ease,background-color .15s ease,border-color .15s ease,box-shadow .15s ease,-webkit-box-shadow .15s ease;
			}
			a {
			    color: #3699ff;
			    text-decoration: none;
			    background-color: transparent;
			}
			.font-size-h5 {
			    font-size: 1.25rem!important;
			}
			.text-muted {
			    color: #b5b5c3!important;
			}
			.text-muted {
			    color: #b5b5c3!important;
			}
			.font-weight-bold {
			    font-weight: 500!important;
			}
			.w-100 {
			    width: 100%!important;
			}
			.rounded-xl {
			    border-radius: 1.25rem!important;
			}
			.mb-30, .my-30 {
			    margin-bottom: 7.5rem!important;
			}
			.w-100 {
			    width: 100%!important;
			}
			.overflow-hidden {
			    overflow: hidden!important;
			}
			.w-100 {
			    width: 100%!important;
			}
			.border-bottom {
			    border-bottom: 1px solid #ebedf3!important;
			}
			.pb-md-10, .py-md-10 {
			    padding-bottom: 2.5rem!important;
			}
			.table-responsive {
			    display: block;
			    width: 100%;
			}
			.table {
			    width: 100%;
			    color: #3f4254;
			    background-color: transparent;
			}
			table {
			    border-collapse: collapse;
			}
			.table:not(.table-bordered) thead td, .table:not(.table-bordered) thead th {
			    border-top: 0;
			}
			.table thead td, .table thead th {
			    font-weight: 600;
			    font-size: 1rem;
			    border-bottom-width: 1px;
			    padding-top: 1rem;
			    padding-bottom: 1rem;
			}
			.table thead th {
			    vertical-align: bottom;
			    border-bottom: 2px solid #ebedf3;
			}
			.table td, .table th {
			    padding: 0.75rem;
			    vertical-align: top;
			    border-top: 1px solid #ebedf3;
			}
			.font-size-lg {
			    font-size: 1.08rem;
			}
			.text-muted {
			    color: #b5b5c3!important;
			}
			.text-muted {
			    color: #b5b5c3!important;
			}
			.font-weight-bolder {
			    font-weight: 600!important;
			}
			.text-uppercase {
			    text-transform: uppercase!important;
			}
			.pb-9, .py-9 {
			    padding-bottom: 2.25rem!important;
			}
			.pt-1, .py-1 {
			    padding-top: 0.25rem!important;
			}
			.pl-0, .px-0 {
			    padding-right: 0!important;
			}
			th {
			    text-align: inherit;
			    text-align: -webkit-match-parent;
			}
			.border-top-0 {
			    border-top: 0!important;
			}
			.font-size-h6 {
			    font-size: 1.175rem!important;
			}
			.text-right {
			    text-align: left!important;
			}
			.mr-15, .mx-15 {
			    margin-left: 3.75rem!important;
			}
			.mb-3, .my-3 {
			    margin-bottom: 0.75rem!important;
			}
			.border-left-md {
			    border-right: 1px solid #ebedf3!important;
			}
			.font-size-h4 {
			    font-size: 1.35rem!important;
			}
			.font-weight-boldest {
			    font-weight: 700;
			}
			.font-size-h1 {
			    font-size: 2rem!important;
			}
			.mb-16, .my-16 {
			    margin-bottom: 4rem!important;
			}
			.border-bottom {
			    border-bottom: 1px solid #ebedf3!important;
			}
			.text-dark-50 {
			    color: #7e8299!important;
			}
			.spans span{
				display: block;
			}
			.pl-md-10, .px-md-10 {
			    padding-right: 2.5rem!important;
			}
			.texts{
				margin-top:-80px;
			}
			.totals{
				margin-right: 120px;
			}
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
		    .svg-icon img{
				width: 200px;
			}
		</style>
	</head>
	<!--end::Head-->

	<body>
		<div class="card card-custom">
		    <div class="card-body p-0">
		        <div class="row justify-content-center pt-8 px-8 pt-md-27 px-md-0">
		            <div class="col-md-12">
		                <div class="row justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
		                	<div class="col-md-6">
		                    	<h1 class="display-4 font-weight-boldest mb-10 py-8">{{trans('main.invoice')}}</h1>
		                	</div>
		                	<div class="col-md-6 text-right texts">
			                    <div class="d-flex flex-column align-items-md-end px-0">
			                        <a href="#" class="mb-5 max-w-200px">
			                            <span class="svg-icon svg-icon-full">
			                                <img src="{{!isset($data->logoFile) ? config('app.BASE_URL') . '/assets/images/whiteLogo.png' : $data->logoFile}}" alt="">
			                            </span>
			                        </a>
			                        <span class="spans align-items-md-end font-size-h5 font-weight-bold text-muted">
			                            <span>{{ $data->companyAddress->servers }}</span> 
			                            <span>{{ $data->companyAddress->address }}</span>
			                            <span>{{ $data->companyAddress->region . ', ' . $data->companyAddress->postal_code  }}</span>
			                            <span>{{ $data->companyAddress->country  }}</span>
			                            <span>{{ $data->companyAddress->tax_id }}</span>
			                        </span>
			                    </div>
			                </div>
		                </div>
		                <div class="rounded-xl overflow-hidden w-100 max-h-md-250px mb-10">
		                    <img src="{{!isset($data->backFile) ? config('app.BASE_URL') . '/assets/tenant/media/bg/bg-invoice-5.jpg' : $data->backFile}}" class="w-100" alt="">
		                </div>
		                <!--begin: Invoice body-->
		                <div class="row border-bottom pb-10">
		                    <div class="col-md-9">
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
		                                    @foreach($data->invoice->items as $key => $item)
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
		                                        $userCredits = $data->invoice->user_credits;
                                        		$discount = $data->invoice->coupon_code != null ? ($data->invoice->discount_type == 1 ? $data->invoice->discount_value : round($prices-$userCredits * $data->invoice->discount_value / 100 ,2)) : 0;
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
		                                            @if($data->invoice->coupon_code != null)
		                                            <p class="mb-0">{{trans('main.coupon_code')}} : {{$data->invoice->coupon_code}}</p>
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
		                        <div class="border-bottom w-100 mt-0 mb-10"></div>
		                        @if($data->invoice->transaction_id)
		                        <div class="row pl-md-10 py-md-10 text-left">
		                            <div class="mb-10 mb-md-0">
		                                <div class="font-weight-bold font-size-h6 mb-3">{{trans('main.transactions')}}</div>
		                                <div class="font-size-lg mb-3">
		                                    <span class="font-weight-bold mr-15">{{ trans('main.transaction_date') }}:</span>
		                                    <span class="text-right">{{ $data->invoice->paid_date }}</span>
		                                </div>
		                                <div class="font-size-lg mb-3">
		                                    <span class="font-weight-bold mr-15">{{ trans('main.paymentGateaway') }}:</span>
		                                    <span class="text-right">{{ $data->invoice->payment_gateaway }}</span>
		                                </div>
		                                <div class="font-size-lg mb-3">
		                                    <span class="font-weight-bold mr-15">{{ trans('main.transaction_id') }}:</span>
		                                    <span class="text-right">{{ $data->invoice->transaction_id }}</span>
		                                </div>
		                                <div class="font-size-lg">
		                                    <span class="font-weight-bold mr-15">{{ trans('main.transaction_price') }}:</span>
		                                    <span class="text-right">{{ $data->invoice->total . ' ' .trans('main.sar') }}</span>
		                                </div>
		                            </div>
		                        </div>
		                        @endif
		                    </div>
		                    <div class="col-md-3 border-left-md pl-md-10 py-md-10 text-left totals">
		                        <div class="font-size-h4 font-weight-bolder text-muted mb-3">{{ trans('main.total') }}</div>
		                        <div class="font-size-h1 font-weight-boldest">{{ $data->invoice->total }}  {{ trans('main.sar') }}</div>
		                        <div class="text-muted font-weight-bold mb-5">{{trans('main.taxesIncluded')}}</div>
		                        <div class="border-bottom w-100 mb-5"></div>
		                        <div class="text-dark-50 font-size-lg font-weight-bold mb-3">{{ trans('main.createdFor') }}.</div>
		                        <div class="font-size-lg font-weight-bold mb-10">
		                            {{ $data->invoice->company }}
		                            <br>
		                            {{ $data->invoice->client }}
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
		                        <div class="text-dark-50 font-size-lg font-weight-bold mb-3">{{trans('main.invoiceId')}}.</div>
		                        <div class="font-size-lg font-weight-bold mb-10">{{trans('main.invoice') . ' #'. ($data->invoice->id + 10000)}}</div>
		                        <div class="text-dark-50 font-size-lg font-weight-bold mb-3">{{ trans('main.pubDate') }}</div>
		                        <div class="font-size-lg font-weight-bold mb-10">{{ date('M d, Y',strtotime($data->invoice->created_at)) }}</div>
		                        <div class="text-dark-50 font-size-lg font-weight-bold mb-3">{{ trans('main.eInvoice') }}.</div>
		                        <div class="font-size-lg font-weight-bold mb-3">
		                            <img src="{{$data->qrImage}}" width="200" height="200">
		                        </div>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>	
	</body>
	<!--end::Body-->
</html>

