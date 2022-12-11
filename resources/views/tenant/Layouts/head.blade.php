<link rel="shortcut icon" href="{{ asset('assets/images/icons/logo.svg') }}">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

<link href="{{asset('assets/tenant/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/tenant/plugins/global/plugins.bundle.css')}}" rel="stylesheet">
<link href="{{asset('assets/tenant/plugins/custom/prismjs/prismjs.bundle.css')}}" rel="stylesheet">
@if(DIRECTION == 'ltr')
<link href="{{ asset('assets/tenant/css/style.bundle.css') }}" rel="stylesheet">
@else
<link href="{{ asset('assets/tenant/css/style.bundle.rtl.css') }}" rel="stylesheet">
@endif
<link href="{{ asset('assets/tenant/css/themes/layout/header/base/light.css') }}" rel="stylesheet">
<link href="{{ asset('assets/tenant/css/themes/layout/header/menu/light.css') }}" rel="stylesheet">
<link href="{{ asset('assets/tenant/css/themes/layout/brand/dark.css') }}" rel="stylesheet">
<link href="{{ asset('assets/tenant/css/themes/layout/aside/dark.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/tenant/plugins/sweet-alert/sweetalert.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/tenant/plugins/sweet-alert/sweetalert.css') }}">
<link href="{{ asset('assets/tenant/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/tenant/css/toastr.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('assets/tenant/css/intlTelInput.css') }}">
<link href="{{ asset('assets/tenant/css/touches.css') }}" rel="stylesheet" type="text/css">

<!-- third party css -->
@livewireStyles
@yield('styles')
<!-- third party css end -->