@extends('central.Layouts.auth.master')
@section('title','تسجيل الدخول')
@section('content')
<div class="row">
	<div class="col-md-7">
		<div class="form login">
			<img class="logo" src="{{asset('assets/images/whiteLogo.png')}}" alt="">
			<div class="form-card">
				<h4>تسجيل الدخول</h4>
				<form class="form-details formLogin" id="loginForm">
					@csrf
					<div class="form-group">
						<label for="">رقم الهاتف</label>
                        <input type="hidden" name="phone">
						<input type="tel" id="telephone" class="inputField">
						<i class="fa fa-phone"></i>
					</div>
					<div class="form-group">
						<label for="">كلمة المرور</label>
						<input type="password" name="password" class="inputField">
						<i class="fa fa-lock"></i>
						<i class="fa fa-eye"></i>
					</div>
					<div class="form-group codes hidden">
						<label for="">كود التحقق</label>
						<input type="text" name="code" class="inputField">
						<i class="fa fa-lock"></i>
					</div>
					<div class="form-group">
						<div class="row">
							<div class="w-50">
								<input type="radio"> تذكرني
							</div>
							<div class="w-50 forget">
								<a href="{{URL::to('/resetPassword')}}">نسيت كلمة المرور؟</a>
							</div>
						</div>
					</div>
					<button class="mediaBtn loginBut">دخول</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="first"></div>
		<div class="second">
			<h2>تسجيل الدخول</h2>
			<p>من خلال هذه الصفحة يمكنك تسجيل الدخول الى حسابك</p>
		</div>
		<img src="{{asset('assets/images/image/Saudi- png.png')}}" alt="">
	</div>
</div>		
@endsection
@section('scripts')
<script src="{{asset('assets/tenant/components/login.js')}}"></script>
@endsection