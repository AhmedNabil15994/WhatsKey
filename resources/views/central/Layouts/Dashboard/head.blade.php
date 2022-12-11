<link rel="shortcut icon" href="{{ asset('assets/images/icons/logo.svg') }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">
<!-- Bootstrap css -->
<link href="{{ asset('assets/dashboard/assets/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" />



<!-- Icons css -->

<!--  Owl-carousel css-->
{{-- <link href="{{ asset('assets/dashboard/assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" /> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/plugins/sweet-alert/sweetalert.css') }}">
<!--  Right-sidemenu css -->
<link href="{{ asset('assets/dashboard/assets/plugins/sidebar/sidebar.css') }}" rel="stylesheet">

<!-- Sidemenu css -->

<!-- Maps css -->
{{-- <link href="{{ asset('assets/dashboard/assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet"> --}}

<!-- Jvectormap css -->
{{-- <link href="{{ asset('assets/dashboard/assets/plugins/jqvmap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" /> --}}


<!--- Color css-->

<!---Skinmodes css-->
<link href="{{ asset('assets/dashboard/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
{{-- <link href="{{ asset('assets/dashboard/assets/libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" /> --}}
<!-- icons -->
<link href="{{ asset('assets/dashboard/assets/css/toastr.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/css/intlTelInput.css') }}">
@if(DIRECTION == 'ltr')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/switcher/css/switcher.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/switcher/css/demo.css') }}">
<link href="{{ asset('assets/dashboard/assets/css/icons.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/css/sidemenu.css') }}">
<!-- style css -->
<link href="{{ asset('assets/dashboard/assets/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('assets/dashboard/assets/css/style-dark.css') }}" rel="stylesheet">

<link id="theme" href="{{ asset('assets/dashboard/assets/css/colors/color.css') }}" rel="stylesheet">
<link href="{{ asset('assets/dashboard/assets/css/skin-modes.css') }}" rel="stylesheet" />
@else
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/switcher/css/switcher-rtl.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard/assets/switcher/css/demo.css') }}">
<link href="{{ asset('assets/dashboard/assets/css-rtl/icons.css') }}" rel="stylesheet">
{{-- <link rel="stylesheet" href="{{ asset('assets/dashboard/assets/plugins/telephoneinput/telephoneinput-rtl.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('assets/dashboard/assets/css-rtl/sidemenu.css') }}">
<link href="{{ asset('assets/dashboard/assets/css-rtl/style.css') }}" rel="stylesheet">
<link href="{{ asset('assets/dashboard/assets/css-rtl/style-dark.css') }}" rel="stylesheet">
<link id="theme" href="{{ asset('assets/dashboard/assets/css-rtl/colors/color.css') }}" rel="stylesheet">
<link href="{{ asset('assets/dashboard/assets/css-rtl/skin-modes.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/dashboard/assets/css-rtl/animate.css') }}" rel="stylesheet">
@endif
<link href="{{ asset('assets/dashboard/assets/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('assets/dashboard/assets/css/touches.css') }}" rel="stylesheet" type="text/css">
<!-- third party css -->
<style>.desktop-logo{height: 3rem;}</style>
@yield('styles')
<!-- third party css end -->