@extends('central.Layouts.auth.master')
@section('title','نسيت كلمة المرور')
@section('content')
<div class="row">
	<div class="col-md-7">
		<div class="form checkAvailability">
			<img class="logo" src="{{asset('assets/images/whiteLogo.png')}}" alt="">
			<div class="form-card">
				<h4>نسيت كلمة المرور</h4>
				<form class="form-details" action="{{ URL::current() }}" method="post">
					@csrf
					<div class="form-group">
						<label for="">رقم الهاتف</label>
                        <input type="hidden" name="phone">
						<input type="tel" id="telephone" class="inputField">
						<i class="fa fa-phone"></i>
					</div>
					<button type="submit" class="mediaBtn loginBut">انطلق</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-5">
		<div class="first"></div>
		<div class="second">
			<h2>نسيت كلمة المرور</h2>
			<p>من خلال هذه الصفحة يمكنك اعادة تعيين كلمة المرور الخاصة بك</p>
		</div>
		<img src="{{asset('assets/images/image/Saudi- png.png')}}" alt="">
	</div>
</div>		
@endsection
@section('scripts')
<script src="{{asset('assets/components/checkAvailability.js')}}"></script>
@endsection