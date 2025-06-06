<!DOCTYPE html>
<html lang="{{ LANGUAGE_PREF }}" dir="{{ DIRECTION }}">
	<head>
		<meta charset="UTF-8" />
	    <!-- IE Compatibility Meta -->
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <!-- First Mobile Meta  -->
		<meta name="viewport" content="width=device-width, height=device-height ,  maximum-scale=1 , initial-scale=1">
		<title>واتس كي | Whats Key | @yield('title')</title>
		<meta name="description" content="#" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/font.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/flaticon.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/animate.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/bootstrap.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/font-awesome.min.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/buttons.css') }}" />
		@if(DIRECTION == 'rtl')
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/bootstrap-rtl.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/style.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/responisve.css') }}" />
		@else
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/ltr.css') }}" />
		@endif
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/dark.css') }}" />
		<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/V5/css/touches.css') }}" />
		<style type="text/css">
			td a {
			    color: inherit;
			}

			a {
			    text-decoration: none!important;
			    outline: none;
			    -webkit-transition: all 0.3s;
			    -moz-transition: all 0.3s;
			    -o-transition: all 0.3s;
			    transition: all 0.3s;
			}
			.float-right {
			    float: left !important;
			}
			.text-left {
			    text-align: right !important;
			}
			.bill .tableBills 
			{
				border-radius: 10px;
				overflow: hidden;
				width:100%;
				margin-top:30px;
				margin-bottom:25px;
			}

			.bill .tableBills thead tr
			{
				background-color:#9AE2DE
			}

			.bill .tableBills thead tr th
			{
				padding:15px 15px;
				font-family: "Tajawal-Bold";
				font-size:14px;
				text-align: center;
			}

			.bill .tableBills tbody tr
			{
				background-color:#F4F5FD
			}

			.bill .tableBills tbody tr td
			{
				padding:30px 15px;
				text-align: center;
				font-size:14px;
				font-family: "Tajawal-Medium";
			}

			.helpPage{
				font-family:Tajawal-Regular;
				text-align: right;
				direction: rtl;
			}
			.helpPage .helpHead
			{
				display:inline-block;
				width: 50%;
				margin-top: 10px;
				float: left;
				text-align: left;
			}
			.helpPage .helpLogo
			{
				display:inline-block;
				width: 50%;
				margin-top: 20px;
				text-align: right;
			}
			.helpLogo img{
				width: 200px;
			}
			.helpPage .helpHead .titleHelp
			{
				font-size:14px;
				font-family: "Tajawal-Bold";
				margin-bottom:25px;
				margin-top:15px;
			}

			.helpPage .helpHead .btnHelp
			{
				width:130px;
				height:40px;
				line-height:40px;
				font-family: "Tajawal-Medium";
				border-radius: 5px;
				margin-bottom:25px;
				background-color:#fff;
				color:#000;
				display:block;
				text-align: center
			}

			.helpPage .helpHead .ticketHead
			{
				width:300px;
				overflow: hidden;
				position:relative;
				height:100px;
				float: left;
				margin-right:20px;
				margin-bottom:20px;
			}

			.helpPage .helpHead .ticketHead:after
			{
				content:"";
				position:absolute;
				left:-9px;
				top:8px;
				height:100%;
				background:url("{{asset('assets/dashboard/assets/V5/images/Subtraction 3.png')}}") no-repeat;
				width:18px;
			}

			.helpPage .helpHead .ticketHead .title
			{
				float:right;
				width:50%;
				background-color:#F6CD02;
				height:100px;
				line-height:100px;
				color:#000;
				font-size:22px;
				font-family: "Tajawal-Bold";
				text-align: center;
			}

			.helpPage .helpHead .ticketHead .numbTicket
			{
				width:50%;
				float:left;
				height:100px;
				background-color:#fff;
				padding-top:10px;
			}

			.helpPage .helpHead .ticketHead .numbTicket .numb
			{
				font-family: "Tajawal-ExtraBold";
				font-size:22px;
				margin-bottom:5px;
				display:block;
				text-align: center
			}

			.helpPage .helpHead .ticketHead .numbTicket a
			{
				width:90px;
				height:35px;
				border-radius: 5px;
				background-color:#00BFB5;
				color:#fff;
				font-size:16px;
				font-family: "Tajawal-Medium";
				display:block;
				margin:0 auto;
				text-align: center;
				line-height:35px;
			}

			.helpPage .detailsHelp
			{
				background-color:#fff;
				border-radius: 10px;
				overflow: hidden;
			}

			.helpPage .detailsHelp .tableDetails thead tr th
			{
				padding:25px 20px;
				font-family: "Tajawal-Bold";
				font-size:14px;
			}

			.helpPage .detailsHelp .tableDetails tr th:not(:last-of-type),
			.helpPage .detailsHelp .tableDetails tr td:not(:last-of-type),
			.helpPage .detailsHelp .tables.bill .tableBills tbody tr td:not(:last-of-type)
			{
				border-left:1px solid #F3F3F3
			}

			.helpPage .detailsHelp .tableDetails tr,
			.helpPage .detailsHelp .tables.bill .tableBills tbody tr
			{
				border-bottom:1px solid #f3f3f3
			}

			.helpPage .detailsHelp .tables.bill .overflowTable
			{
				border-radius: 10px;
				border:1px solid #F3F3F3;
				margin-bottom:30px;
			}

			.helpPage .detailsHelp .tables.bill .overflowTable .tableBills
			{
				margin:0;
			}


			.helpPage .detailsHelp  .tables.bill .tableBills thead tr th,
			.helpPage .detailsHelp .tables.bill .tableBills tbody tr td
			{
				padding-left:15px;
				padding-right:15px;
			}

			.helpPage .detailsHelp .tableDetails tbody tr td
			{
				padding:25px 20px;
				font-family: "Tajawal-Medium";
				font-size:14px;
			}

			.helpPage .detailsHelp .tableDetails tbody tr td svg
			{
				display:block;
				margin:0 auto;
				max-width:100px;
			}

			.helpPage .detailsHelp .tables 
			{
				padding:40px 15px 35px;
			}

			.helpPage .detailsHelp .tables.bill .tableBills tbody tr
			{
				background:none;
			}

			.helpPage .detailsHelp .tables.bill .nextPrev .btnNext:first-of-type
			{
				background-color:#C2C8D5;
				color:#3D5075
			}

		</style>
	</head>
	<!--end::Head-->

	<body class="bodyCpanel overflowH">
		<!-- Begin page -->
		<div class="cpanelStyle activeMenu">
			<div class="containerCpanel formNumbers">
				<div id="helpPage">
					<div class="helpPage">
						<div class="helpHead">
							<div class="ticketHead">
								<h2 class="title"> {{ trans('main.invoice') }}</h2>
								<div class="numbTicket">
									<span class="numb">#{{ $data->invoice->id + 10000 }}</span>
									<a href="#">{{ trans('main.invoice_status_'.$data->invoice->status) }}</a>
								</div>
							</div>
						</div>
						<div class="helpLogo">
							<img src="{{ asset('assets/images/whiteLogo.png') }}" alt="">
						</div>
						<div class="clearfix"></div>
						<div class="detailsHelp">
							<div class="overflowTable">
								<table class="tableDetails">
									<thead>
										<tr>
											<th>{{ trans('main.pubDate') }}</th>
											<th>{{ trans('main.due_date') }}</th>
											<th>{{ trans('main.paymentMethod') }}</th>
											<th>{{ trans('main.appName') }}</th>
											<th>{{ trans('main.createdFor') }}</th>
						   					<th>{{ trans('main.eInvoice') }}</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>{{ date('M d, Y',strtotime($data->invoice->created_at)) }}</td>
											<td>{{ date('M d, Y',strtotime($data->invoice->due_date)) }}</td>
											<td>{{ $data->invoice->status == 1 ? $data->invoice->payment_gateaway : '-------' }}</td>
											<td>
												{{ $data->companyAddress->servers }}
												<br>
												{{ $data->companyAddress->address }}
												<br>
												{{ $data->companyAddress->region . ', ' . $data->companyAddress->postal_code  }}
												<br>
												{{ $data->companyAddress->city }}
												<br>
												{{ $data->companyAddress->country  }}
												<br>
												{{ trans('main.tax_id') }}: {{ $data->companyAddress->tax_id }}
											</td>
											<td>
												{{ $data->invoice->company }}
												<br>
												{{ $data->invoice->client }}
												<br>
												{{ (isset($data->paymentObj) ? $data->paymentObj->address : '') }}
												<br>
												{{ (isset($data->paymentObj) ? $data->paymentObj->city : '') . ', ' . (isset($data->paymentObj) ? $data->paymentObj->region : '') . ', ' . (isset($data->paymentObj) ? $data->paymentObj->postal_code : '')  }}
												<br>
												{{ (isset($data->paymentObj) ? $data->paymentObj->country : '')  }}
												<br>
												@if((isset($data->paymentObj) ? $data->paymentObj->tax_id : ''))
												{{ trans('main.tax_id') }}: {{ $data->paymentObj->tax_id }}
						                        @endif
											</td>
											<td>
												<img src="{{$data->qrImage}}" width="100" height="100">
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="tables bill">
								<div class="overflowTable">
						            <table class="tableBills">
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
					                        @foreach($data->invoice->items as $key => $item)
					                        @php
		                                        $prices += $item['price']; 
		                                    @endphp
					                        <tr class="mainRow">
					                            <td>{{ $key+1 }}</td>
					                            <td colspan="3">
					                                <p>
					                                    <a href="#">{{ $item['title'] }}</a><br>
					                                    <small><b>{{ trans('main.extra_type') }}:</b> {{ trans('main.'.$item['type']) }} </small>
					                                </p>
					                            </td>
					                            <td>{{ $item['quantity'] }}</td>
					                            <td class="text-center">
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
		                                        <td colspan="5" class="font-size-h6 text-right">{{trans('main.userCredits')}}</td>
		                                        <td class="font-weight-bolder font-size-h6 text-right"> <span class="userCredits">{{$userCredits}}</span> <sup>{{trans('main.sar2')}}</sup> </td>
		                                    </tr>
		                                    @endif
		                                    <tr>
		                                        <td colspan="5" class="font-size-h6 text-right">{{trans('main.discount')}}</td>
		                                        <td class="font-weight-bolder font-size-h6 text-right"> 
		                                            <span class="discount">{{$discount}}</span> <sup>{{trans('main.sar2')}}</sup> 
		                                            @if($data->invoice->coupon_code != null)
		                                            <p class="mb-0">{{trans('main.coupon_code')}} : {{$data->invoice->coupon_code}}</p>
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
					                        <input type="hidden" name="invoice_id" value="{{ $data->invoice->id }}">
							            </tbody>
							        </table>
								</div>	  
								@if($data->invoice->transaction_id)
								<div class="overflowTable">
						            <table class="tableBills">
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
						                    <tr class="mainRow">
						                        <td>{{ $key+1 }}</td>
						                        <td>
						                            <p class="m-0 d-inline-block align-middle font-16">
						                                <a href="#" class="text-reset font-family-secondary">{{ $data->invoice->paid_date }}</a><br>
						                            </p>
						                        </td>
						                        <td>{{ $data->invoice->payment_gateaway }}</td>
						                        <td>{{ $data->invoice->transaction_id }}</td>
						                        <td>{{ $data->invoice->total }} {{ trans('main.sar') }}</td>
						                    </tr>
						                </tbody>
							        </table>
								</div>
								@endif	 			
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="{{ asset('assets/dashboard/assets/V5/js/jquery-1.11.2.min.js') }}"></script>
		<script src="{{ asset('assets/dashboard/assets/V5/js/jquery-ui.js') }}"></script>
		<script src="{{ asset('assets/dashboard/assets/V5/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('assets/dashboard/assets/V5/js/wow.min.js') }}"></script>
		<script src="{{ asset('assets/dashboard/assets/V5/plugins/moment/moment.js') }}"></script>
		<script src="{{ asset('assets/dashboard/assets/V5/components/multi-lang.js') }}"></script>
		<script src="{{ asset('assets/dashboard/assets/V5/js/utils.js') }}" type="text/javascript"></script>
		<script src="{{ asset('assets/dashboard/assets/V5/js/custom.js') }}"></script>
		<script src="{{ asset('assets/dashboard/assets/V5/components/globals.js') }}"></script>		
	</body>
	<!--end::Body-->
</html>

