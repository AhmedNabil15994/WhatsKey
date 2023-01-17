<!DOCTYPE html>
<html lang="{{ LANGUAGE_PREF }}" dir="{{ DIRECTION }}">
	<head>
		<meta charset="utf-8" />
		<title>واتس كي | WhatsKey | @yield('title')</title>
		<meta name="description" content="#" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		@yield('extra-metas')
		@include('tenant.Layouts.head')
	</head>
	<!--end::Head-->
	
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		@include('tenant.Layouts.mobileHeader')
		@php 
			$breadcrumbs = []; 
			$varObj = \App\Models\CentralVariable::getVar('NOTIFICATION');
			$varObj = $varObj != null ? json_decode($varObj) : null;
		@endphp
		<!-- Begin page -->
		<input type="hidden" name="countriesCode" value="{{ Helper::getCountryCode() ? Helper::getCountryCode()->countryCode : 'sa' }}">
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-row flex-column-fluid page">
				@include('tenant.Layouts.sidebar')
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
					@include('tenant.Layouts.header')
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						@yield('breadcrumbs')
						<div class="d-flex flex-column-fluid">
							<div class="container">		
								@if(!in_array(Request::segment(1),['QR','msgsArchive','addGroupNumbers']) && (Request::segment(1) != 'profile' && Request::segment(2) != 'apiGuide') && IS_ADMIN)
									@livewire('check-reconnection',[
										'requestSemgent' => Request::segment(1),
										'addons' => Session::get('addons'),
										'tenant_id' => TENANT_ID
									])
								@endif			

								@if($varObj)
								<div class="alert alert-custom alert-warning" role="alert">
									<div class="alert-icon">
										<i class="flaticon-questions-circular-button"></i>
									</div>
									<h5 class="alert-heading pt-2" style="display: block">
										{{ $varObj->{'title_'.LANGUAGE_PREF} }}
	    								<p>{{ $varObj->{'description_'.LANGUAGE_PREF} }}</p>
									</h5>
								</div>
								@endif
								@yield('content')
							</div>
						</div>
					</div>
					@include('tenant.Layouts.footer')
				</div>
			</div>
		</div>
		@yield('modals')

		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</div>
		<!--end::Scrolltop-->
		@include('tenant.Layouts.scripts')
        @include('tenant.Partials.notf_messages')
	</body>
	<!--end::Body-->
</html>