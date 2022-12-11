<!DOCTYPE html>
<html lang="{{ LANGUAGE_PREF }}" dir="{{ DIRECTION }}">
	<head>
		<meta charset="utf-8" />
		<title>واتس كي | WhatsKey | @yield('title')</title>
		<meta name="description" content="#" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		@yield('extra-metas')
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		@include('central.Layouts.Dashboard.head')
	</head>
	<!--end::Head-->
	
	<body class="main-body tena light-theme app sidebar-mini active leftmenu-color">
		<!-- Begin page -->
		<input type="hidden" name="countriesCode" value="{{ Helper::getCountryCode() ? Helper::getCountryCode()->countryCode : 'sa' }}">
		@include('central.Layouts.Dashboard.sidebar')
		
		<!-- main-content -->
		<div class="main-content app-content">
			@include('central.Layouts.Dashboard.header')
			<!-- container -->
			<div class="container-fluid mg-t-20">
				@include('central.Layouts.Dashboard.breadcrumb')
				@yield('content')
			</div>
		</div>

		@include('central.Layouts.Dashboard.rightSideBar')
		
		@yield('modals')

		@include('central.Layouts.Dashboard.footer')
		@include('central.Layouts.Dashboard.scripts')
        @include('central.Partials.notf_messages')
	</body>
	<!--end::Body-->
</html>