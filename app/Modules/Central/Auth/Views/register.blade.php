@extends('central.Layouts.auth.master')
@section('title','انشاء حساب جديد')
@section('content')
<div class="row">
	<div class="col-md-7">
		<div class="form">
			<img class="logo" src="{{asset('assets/images/whiteLogo.png')}}" alt="">
			<div class="form-card">
				<h4>تسجيل جديد</h4>
				<form class="form-details" action="{{ URL::to('/register') }}" method="post">
					@csrf
					<span>المعلومات الشخصية</span>
					<div class="form-group">
						<label for="">الاسم</label>
						<input type="text" name="name" class="inputField">
						<i class="fa-regular fa-user"></i>
					</div>
					<div class="form-group">
						<label for="">الشركة</label>
						<input type="text" name="company" class="inputField">
						<i class="fa-regular fa-user"></i>
					</div>
					<div class="form-group">
						<label for="">البريد الالكتروني</label>
						<input type="email" name="email" class="inputField">
						<i class="fa-regular fa-envelope"></i>
					</div>
					<div class="form-group">
						<label for="">النطاق</label>
						<input type="text" name="domain" class="inputField">
						<i class="fa-regular fa-envelope"></i>
					</div>
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
					<p>بالتسجيل أقر بأني قرأت شروط الاستخدام وأوافق عليها</p>
					<button type="submit" class="mediaBtn loginBut">تسجيل</button>
				</form>
				<p class="lastP">لديك حساب؟ <a href="{{URL::to('/login')}}">تسجيل دخول</a></p>
				<p class="lastP">او <a href="{{URL::to('/checkAvailability')}}">التسجيل من خلال رقم الهاتف</a></p>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="first"></div>
		<div class="second">
			<h2>انشاء حساب جديد</h2>
			<p>من خلال هذه الصفحة يمكنك التسجيل والتمتع بخدمة واتس كي</p>
		</div>
		<img src="{{asset('assets/images/image/Saudi- png.png')}}" alt="">
	</div>
</div>		
@endsection
@section('scripts')
<script src="{{asset('assets/components/register.js')}}"></script>
@endsection