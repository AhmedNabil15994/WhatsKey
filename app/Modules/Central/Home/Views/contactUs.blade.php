@extends('central.Layouts.master')
@section('title','اتصل بنا')
@section('content')
<div class="firstSection">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2>اتصل بنا</h2>
				<p>الرئيسية <i class="fa fa-angle-left"></i> اتصل بنا</p>
			</div>
			<div class="col-md-6">
				<img src="{{asset('assets/images/Illustration/undraw_contact_us_re_4qqt (1).svg')}}" alt="">
			</div>
		</div>
	</div>
</div>


<div class="contactUs" id="dataSection">
	<div class="container">
		<div class="row">
			<h2>يسعدنا تواصلك معنا</h2>
			<div class="row">
				<div class="col-md-3 contactCards">
					<div class="contactCard">
						<img src="{{asset('assets/images/icons/customer service.svg')}}" alt="">
						<span>خدمة المبيعات</span>
						<span>91295855</span>
					</div>
					<div class="contactCard">
						<img src="{{asset('assets/images/icons/email.svg')}}" alt="">
						<span>دعم البريد</span>
						<span>sales@whatskey.net</span>
					</div>
					<div class="contactCard">
						<img src="{{asset('assets/images/icons/service.svg')}}" alt="">
						<span>خدمة العملاء</span>
						<span>91295855</span>
					</div>
				</div>
				<div class="col-md-9">
					<form class="form" method="post" action="{{URL::current()}}">
						@csrf
						<input type="text" name="name" value="{{old('name')}}" placeholder="الاسم">
						<input type="text" name="phone" value="{{old('phone')}}" placeholder="رقم الهاتف">
						<input type="text" name="email" value="{{old('email')}}" placeholder="البريد الالكتروني">
						<input type="text" name="title" value="{{old('title')}}" placeholder="عنوان الرسالة">
						<textarea name="message" placeholder="الرسالة">{{old('message')}}</textarea>
						<button type="submit" class="mediaBtn">ارسل الان</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
@endsection