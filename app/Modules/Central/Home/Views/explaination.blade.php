@extends('central.Layouts.master')
@section('title','الأسئلة الشائعة')
@section('content')
<div class="firstSection">
	<div class="container">
		<div class="row privacyRoot">
			<div class="col-md-6">
				<h2>الشرح</h2>
				<p>الرئيسية <i class="fa fa-angle-left"></i> الشرح</p>
			</div>
			<div class="col-md-6">
				<img src="{{asset('assets/images/Illustration/undraw_online_video_re_fou2.svg')}}" alt="">
			</div>
		</div>
	</div>
</div>


<div class="explaination" id="dataSection">
	<div class="container">
		<div class="row">
			<h2>قائمة الفيديوهات</h2>
			<div class="row">
				<div class="col-md-4">
					<div class="mediaData">
						<img src="{{asset('assets/images/image/Saudi.jpg')}}" alt="">
						<span><i class="fa fa-play"></i></span>
						<button class="mediaBtn">مشاهدة</button>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mediaData">
						<img src="{{asset('assets/images/image/Saudi.jpg')}}" alt="">
						<span><i class="fa fa-play"></i></span>
						<button class="mediaBtn">مشاهدة</button>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mediaData">
						<img src="{{asset('assets/images/image/Saudi.jpg')}}" alt="">
						<span><i class="fa fa-play"></i></span>
						<button class="mediaBtn">مشاهدة</button>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mediaData">
						<img src="{{asset('assets/images/image/Saudi.jpg')}}" alt="">
						<span><i class="fa fa-play"></i></span>
						<button class="mediaBtn">مشاهدة</button>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mediaData">
						<img src="{{asset('assets/images/image/Saudi.jpg')}}" alt="">
						<span><i class="fa fa-play"></i></span>
						<button class="mediaBtn">مشاهدة</button>
					</div>
				</div>
				<div class="col-md-4">
					<div class="mediaData">
						<img src="{{asset('assets/images/image/Saudi.jpg')}}" alt="">
						<span><i class="fa fa-play"></i></span>
						<button class="mediaBtn">مشاهدة</button>
					</div>
				</div></div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('scripts')
@endsection