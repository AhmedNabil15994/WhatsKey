@extends('central.Layouts.auth.master')
@section('title','أدخل رمز التحقق')
@section('content')
<div class="row">
	<div class="col-md-7">
		<div class="form checkAvailability">
			<img class="logo" src="{{asset('assets/images/whiteLogo.png')}}" alt="">
			<div class="form-card">
				<h4>أدخل رمز التحقق</h4>
				<form class="form-details" action="{{ Session::has('t_reset') && Session::get('t_reset') == 1 ? URL::to('/checkResetPassword') : URL::to('/checkAvailabilityCode') }}" method="post">
					@csrf
					<div class="form-group">
						<label for="">رمز التحقق</label>
                        <input type="hidden" name="phone" value="{{ $data->phone }}">
						<input type="text" class="inputField" name="code">
						<i class="fa fa-phone"></i>
					</div>
					<button type="submit" class="mediaBtn loginBut">انطلق</button>
				</form>
				<p class="lastP">لديك حساب؟ <a href="{{URL::to('/login')}}">تسجيل دخول</a></p>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="first"></div>
		<div class="second">
			<h2>أدخل رمز التحقق</h2>
			<p>من خلال هذه الصفحة يمكنك التحقق من رمز التحقق المرسل الي الواتساب الخاص بك</p>
		</div>
		<img src="{{asset('assets/images/image/Saudi- png.png')}}" alt="">
	</div>
</div>		
@endsection
@section('scripts')
@endsection