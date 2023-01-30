@extends('central.Layouts.master')
@section('title','من نحن')
@section('content')
<div class="firstSection">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2>من نحن</h2>
				<p>الرئيسية <i class="fa fa-angle-left"></i> من نحن</p>
			</div>
			<div class="col-md-6">
				<img src="{{asset('assets/images/Illustration/undraw_connected_re_lmq2.svg')}}" alt="">
			</div>
		</div>
	</div>
</div>


<div class="whoUs" id="dataSection">
	<div class="container">
		<div class="row">
			<h2>واتس كيي</h2>
			<p>
				تواصل بذكاء مع عملائك و اجعل منشأتك تنبض <br>بالحياة باستخدام واجهات برمجة <br>تطبيقات قوية وموثوق بها
			</p>
			<h2>من نحن</h2>
			<p>
				خدمة واتس كي تميزنا من خلال سنوات الخبرة بتقديم باقات متكاملة من المنتجات والحلول والخدمات التقنية المتنوعة لنسطر نجاحات متوالية قائمة على سياستنا الرامية إلى خلق وإيجاد خدمات نوعية متميزة تلبي تطلعات عملائنا في كافة القطاعات المختلفة بدأ بالأفراد والشركات بمختلف أحجامها وانتهاءً بالقطاعات والمنظمات الحكومية
			</p>
		</div>
	</div>
</div>

<div class="goals">
	<h2>هدفنا أن نوحد قنوات التواصل بين منشأتك وعملائك</h2>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="goal">
					<div class="frame">
						<img src="{{asset('assets/images/icons/1.svg')}}" alt="">
					</div>
					<h4>الثقة و الأمان</h4>
					<p>إننا ملتزمين بالحماية والحفاظ على سرية أي معلومات متعلقة ببياناتك وسنسعى جاهدين في جميع الأوقات لضمان سرية هذه المعلومات وحمايتها</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="goal">
					<div class="frame">
						<img src="{{asset('assets/images/icons/2.svg')}}" alt="">
					</div>
					<h4>رضاكم هو هدفنا</h4>
					<p>نضمن لك الجودة العالية و الخدمة الموثوقة وبأسعار مناسبة ونقدم لك خدمة الدعم الفني على مدار الساعة وفي كافة أيام الأسبوع</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="goal">
					<div class="frame">
						<img src="{{asset('assets/images/icons/3.svg')}}" alt="">
					</div>
					<h4>خدمات متكاملة</h4>
					<p>فريق عمل متكامل يعمل على تطوير خدماتنا المقدمة لك لضمان سهولة الاستخدام وتوفير جميع الخدمات المرتبطة بتحسين عملية تواصلك مع عملائك</p>
				</div>
			</div>
		</div>
	</div>
</div>

{{-- <div class="opinions">
	<h2>أراء عملاؤنا</h2>
	<div class="container">
		<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
		  	<div class="carousel-inner">
		    	<div class="carousel-item active">
		      		<div class="row">
						<div class="col-md-6">
							<div class="opinion">
								<div class="partner">
									<img src="{{asset('assets/images/image/3+.jpg')}}" alt="">
									<div class="clearfix"></div>
								</div>
								<h4>مركز الخوادم الرقمية</h4>
								<p>شكرا على خدماتكم المميزة في ربط الواتس اب في إدارة وحجز برامجنا الإستشارية والتدريبية</p>
								<p>
									<i class="fa fa-star"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
								</p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="opinion">
								<div class="partner">
									<img src="{{asset('assets/images/image/1+.jpg')}}" alt="">
									<div class="clearfix"></div>
								</div>
								<h4>إدارة التطوير المهني التعليمي بعسير</h4>
								<p>شكرا على خدماتكم المميزة في ربط الواتس اب في إدارة وحجز برامجنا الإستشارية والتدريبية</p>
								<p>
									<i class="fa fa-star"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
									<i class="fa fa-star active"></i>
								</p>
							</div>
						</div>
					</div>
		    	</div>
		 	</div>
		  	<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
		    	<i class="fa fa-angle-left"></i>
		  	</a>
		  	<a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
		    	<i class="fa fa-angle-right"></i>
		  	</a>
		</div>
	</div>
</div> --}}
@endsection
@section('scripts')
@endsection