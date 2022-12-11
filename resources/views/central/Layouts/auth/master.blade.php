<!DOCTYPE html>
<html lang="{{ LANGUAGE_PREF }}" dir="{{ DIRECTION }}">
	<head>
		<meta charset="UTF-8" />
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, height=device-height ,  maximum-scale=1 , initial-scale=1">
		<title>واتس كي | WhatsKey | @yield('title')</title>
		<meta name="description" content="#" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		@yield('extra-metas')
		@include('central.Layouts.auth.head')
	</head>
	
	<body>
		<div class="root">
			<input type="hidden" name="countriesCode" value="{{ Helper::getCountryCode() ? Helper::getCountryCode()->countryCode : 'sa' }}">
			@yield('content')
			@include('central.Layouts.auth.scripts')
	        @include('central.Partials.notf_messages')
	    </div>
	</body>
</html>