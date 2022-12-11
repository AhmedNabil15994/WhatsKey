<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>     
<script src="{{asset('assets/js/index.js')}}"></script>

<script src="{{ asset('assets/plugins/intlTelInput-jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweet-alert/jquery.sweet-alert.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>

@yield('topScripts')
<script src="{{ asset('assets/plugins/toastr.min.js') }}"></script>
<script src="{{ asset('assets/components/notifications.js') }}"></script>
<script src="{{ asset('assets/components/multi-lang.js') }}"></script>

@yield('scripts')
