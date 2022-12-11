@extends('central.Layouts.auth.master')
@section('title','تغيير كلمة المرور')
@section('content')
<div class="row">
	<div class="col-md-7">
		<div class="form checkAvailability">
			<img class="logo" src="{{asset('assets/images/whiteLogo.png')}}" alt="">
			<div class="form-card">
				<h4>تغيير كلمة المرور</h4>
				<form class="form-details" action="{{ URL::to('/completeReset') }}" method="POST">
					@csrf
					<div class="form-group">
						<label for="">كلمة المرور</label>
						<input type="password" name="password" class="inputField">
						<i class="fa fa-lock"></i>
						<i class="fa fa-eye"></i>
					</div>
					<div class="form-group">
						<label for="">تأكيد كلمة المرور</label>
						<input type="password" name="password_confirmation" class="inputField">
						<i class="fa fa-lock"></i>
						<i class="fa fa-eye"></i>
					</div>
					<button type="submit" class="mediaBtn">انطلق</button>
				</form>
				<p class="lastP">لديك حساب؟ <a href="{{URL::to('/login')}}">تسجيل دخول</a></p>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="first"></div>
		<div class="second">
			<h2>تغيير كلمة المرور</h2>
			<p>من خلال هذه الصفحة يمكنك تغيير كلمة المرور</p>
		</div>
		<img src="{{asset('assets/images/image/Saudi- png.png')}}" alt="">
	</div>
</div>		
@endsection
@section('scripts')
<script src="{{asset('assets/components/login.js')}}"></script>
@endsection