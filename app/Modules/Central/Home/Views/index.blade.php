@extends('central.Layouts.master')
@section('title','الرئيسية')
@section('content')
<div class="sliderSection">
	<div class="container">
		<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="false">
		  	<div class="carousel-inner">
		    	@foreach($data->sliders as $key => $slider)
		    	<div class="carousel-item {{$key == 0 ? 'active' : ''}}">
		      		<img src="{{$slider->photo}}" alt="...">
		      		<div class="carousel-caption">
		        		{!! $slider->description !!}
		        		<button>اشترك الان</button>
		      		</div>
		    	</div>
		    	@endforeach
		 	</div>
		  	<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
		    	<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    	<span class="sr-only"></span>
		  	</a>
		  	<a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
		    	<span class="carousel-control-next-icon" aria-hidden="true"></span>
		    	<span class="sr-only"></span>
		  	</a>
		</div>
</div>
</div>

<div class="rates">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="rate">
					<h2>80%</h2>
					<p>نسبة انخفاض وقت معالجة الطلبات</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="rate">
					<h2>95%</h2>
					<p>معدل الدقة في الرد على استفسارات العملاء</p>
				</div>
			</div>
			<div class="col-md-4 last">
				<div class="rate">
					<h2>73%</h2>
					<p>زيادة في معدل فتح الاشعارات</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="qrSection">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="qrDetails">
					<h4>تواصل بذكاء مع عملائك واجعل منشأتك تنبض بالحياة باستخدام واجهات برمجة تطبيقات قوية وموثوق بها</h4>
					<p>ودك تجرب خدمة واتس كي بووت ؟ +201558651994 أضف الرقم الي قائمة جهات الاتصال الخاصة بك وارسل رسالة تقول 'مرحبا' من خلال تطبيق WhatsApp او امسح رمز QR</p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="qrImage">
					<img src="{{asset('assets/images/image/QR.png')}}">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="clients1">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="details">
					<h4>تواصل مع عملائك بشكل أسرع</h4>
					<p>تواصل مع عملائك بطريقة مبتكرة من خلال ربط موقعك الإلكتروني أو متجرك أو تطبيقك أو أنظمة مبيعاتك بالواتساب برنامج المحادثات الأكثر استخدامة في العالم</p>
				</div>
			</div>
			<div class="col-md-6">
				<div class="mediaData">
					<img src="{{asset('assets/images/image/Saudi.jpg')}}" alt="">
					<span><i class="fa fa-play"></i></span>
					<button class="mediaBtn">مشاهدة</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="clients2" id="advantages">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3>تواصل مع عملائك بطريقة مبتكرة من خلال ربط موقعك الإلكتروني أو متجرك أو تطبيقك أو أنظمة مبيعاتك بالواتساب برنامج المحادثات الأكثر استخدامة في العالم</h3>
				<ul>
					<li> يعمل 24 ساعة دون توقف.</li>
					<li>يجيب على الاستفسارات بسرعة ودقة فائقة.</li>
					<li> واجهة سهلة الاستخدام ومتعددة اللغات.</li>
					<li>تقارير و إحصائيات شاملة ومباشرة.</li>
					<li>امكانية ارسال الوسائط النصية والمرئية.</li>
					<li>قوالب نصية معدة مسبقا لمراسلاتك.</li>
					<li>ميزة التحقق بخطوتين لحماية بياناتك.</li>
					<li>توفر التكاليف وتزيد من الكفاءة.</li>
					<li>دعم فني متواصل لضمان استقرار الخدمة.</li>
					<li>امكانية الربط البرمجي مع عدة لغات برمجية.</li>
					<li class="last">عدد لامحدود من المحادثات نفس اللحظة.</li>
					<div class="clearfix"></div>
				</ul>
			</div>
			<img src="{{asset('assets/images/image/hand.png')}}" alt="">
		</div>
	</div>
</div>

<div class="packages opinions" id="packages">
	<h2>الباقات</h2>
	<div class="container">
		<div id="carouselExampleCaptionss" class="carousel slide" data-bs-ride="false">
		  	<div class="carousel-inner">
		  		@php $memberships = array_chunk($data->memberships, 4); @endphp

		  		@foreach($memberships as $key => $oneArray)
		    	<div class="carousel-item {{$key  == 0 ? 'active' : ''}}">
		    		<div class="row">
		  				@foreach($oneArray as $memberKey => $membership)
		    			<div class="col-md-3">
							<div class="package {{$memberKey == 0 && $key == 0 ? "" : "closed"}}">
								<div class="pkgDetails">
									<h3 class="title">{{$membership->title}}</h3>
									<h3 class="price" data-monthly="{{$membership->monthly_after_vat}}" data-annual="{{$membership->annual_after_vat}}">
										<span class="value">{{$membership->monthly_after_vat}}</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										@foreach($membership->featruesArr as $feature)
										<li> <i class="icon fa fa-check"></i> {{$feature}}</li>
										@endforeach
										{{-- <li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li> --}}
									</ul>
									<a href="{{URL::to('/checkAvailability?membership='.$membership->id.'&duration=1')}}" class="mediaBtn">اشترك الان</a>
								</div>
								<div class="more">
									<span class="mor {{$memberKey == 0 && $key == 0 ? "hidden" : ""}}">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les {{$memberKey == 0 && $key == 0 ? "" : "hidden"}}">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
		    			@endforeach
		    		</div>
		    	</div>
		  		@endforeach
		    	{{-- <div class="carousel-item active">
		      		<div class="row">
						<div class="col-md-3">
							<div class="package">
								<div class="pkgDetails">
									<h3 class="title">واتس كي API</h3>
									<h3 class="price" data-monthly="345" data-annual="3450">
										<span class="value">345</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										<li> <i class="icon fa fa-check"></i> عدد قنوات الاتصال (1)</li>
										<li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
									</ul>
									<button class="mediaBtn">اشترك الان</button>
								</div>
								<div class="more">
									<span class="mor hidden">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="package closed">
								<div class="pkgDetails">
									<h3 class="title">المحادثة المباشرة</h3>
									<h3 class="price" data-monthly="345" data-annual="3450">
										<span class="value">345</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										<li> <i class="icon fa fa-check"></i> عدد قنوات الاتصال (1)</li>
										<li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
									</ul>
									<button class="mediaBtn">اشترك الان</button>
								</div>
								<div class="more">
									<span class="mor">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les hidden">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="package closed">
								<div class="pkgDetails">
									<h3 class="title">الرسائل الجماعية</h3>
									<h3 class="price" data-monthly="345" data-annual="3450">
										<span class="value">345</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										<li> <i class="icon fa fa-check"></i> عدد قنوات الاتصال (1)</li>
										<li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
									</ul>
									<button class="mediaBtn">اشترك الان</button>
								</div>
								<div class="more">
									<span class="mor">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les hidden">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="package closed">
								<div class="pkgDetails">
									<h3 class="title">واتس كي بوت</h3>
									<h3 class="price" data-monthly="345" data-annual="3450">
										<span class="value">345</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										<li> <i class="icon fa fa-check"></i> عدد قنوات الاتصال (1)</li>
										<li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
									</ul>
									<button class="mediaBtn">اشترك الان</button>
								</div>
								<div class="more">
									<span class="mor">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les hidden">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
					</div>
		    	</div>

		    	<div class="carousel-item">
		      		<div class="row">
						<div class="col-md-3">
							<div class="package">
								<div class="pkgDetails">
									<h3 class="title">واتس كي API</h3>
									<h3 class="price" data-monthly="345" data-annual="3450">
										<span class="value">345</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										<li> <i class="icon fa fa-check"></i> عدد قنوات الاتصال (1)</li>
										<li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
									</ul>
									<button class="mediaBtn">اشترك الان</button>
								</div>
								<div class="more">
									<span class="mor hidden">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="package closed">
								<div class="pkgDetails">
									<h3 class="title">المحادثة المباشرة</h3>
									<h3 class="price" data-monthly="345" data-annual="3450">
										<span class="value">345</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										<li> <i class="icon fa fa-check"></i> عدد قنوات الاتصال (1)</li>
										<li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
									</ul>
									<button class="mediaBtn">اشترك الان</button>
								</div>
								<div class="more">
									<span class="mor">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les hidden">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="package closed">
								<div class="pkgDetails">
									<h3 class="title">الرسائل الجماعية</h3>
									<h3 class="price" data-monthly="345" data-annual="3450">
										<span class="value">345</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										<li> <i class="icon fa fa-check"></i> عدد قنوات الاتصال (1)</li>
										<li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
									</ul>
									<button class="mediaBtn">اشترك الان</button>
								</div>
								<div class="more">
									<span class="mor">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les hidden">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="package closed">
								<div class="pkgDetails">
									<h3 class="title">واتس كي بوت</h3>
									<h3 class="price" data-monthly="345" data-annual="3450">
										<span class="value">345</span> 
										<span class="currency">ر.س</span>
									</h3>
									<p>شامل ضريبة القيمة المضافة</p>
								</div>
								<div class="pkgDates">
									<a class="period monthly active">شهري</a>
									<a class="period annual">سنوي</a>
								</div>
								<div class="pkgFeatures">
									<ul class="featrues">
										<li> <i class="icon fa fa-check"></i> عدد قنوات الاتصال (1)</li>
										<li> <i class="icon fa fa-check"></i> ارسال من رقمك الخاص</li>
										<li> <i class="icon fa fa-check"></i> آلاف رسالة يوميا كحد أقص</li>
										<li> <i class="icon fa fa-times"></i> المحادثة المباشرة</li>
										<li> <i class="icon fa fa-check"></i> أرشيف الرسائل</li>
										<li> <i class="icon fa fa-check"></i> الرسائل الجماعية</li>
										<li> <i class="icon fa fa-times"></i> واتس كي بوت</li>
										<li> <i class="icon fa fa-check"></i> الدعم الفني</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
										<li> <i class="icon fa fa-check"></i> مركز المساعدة</li>
									</ul>
									<button class="mediaBtn">اشترك الان</button>
								</div>
								<div class="more">
									<span class="mor">المزيد <i class="icon fa fa-angle-down"></i></span>
									<span class="les hidden">أقل <i class="icon fa fa-angle-up"></i></span>
								</div>
							</div>
						</div>
					</div>
		    	</div> --}}
		 	</div>
		  	<a class="carousel-control-prev" href="#carouselExampleCaptionss" role="button" data-slide="prev">
		    	<i class="fa fa-angle-left"></i>
		  	</a>
		  	<a class="carousel-control-next" href="#carouselExampleCaptionss" role="button" data-slide="next">
		    	<i class="fa fa-angle-right"></i>
		  	</a>
		</div>
		
	</div>
</div>

{{-- <div class="partners">
	<h2>شركاؤنا</h2>
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="partner">
					<img src="{{asset('assets/images/image/1+.jpg')}}" alt="">
				</div>
			</div>
			<div class="col-md-4">
				<div class="partner">
					<img src="{{asset('assets/images/image/2+.jpg')}}" alt="">
				</div>
			</div>
			<div class="col-md-4">
				<div class="partner">
					<img src="{{asset('assets/images/image/3+.jpg')}}" alt="">
				</div>
			</div>
			<div class="col-md-4">
				<div class="partner">
					<img src="{{asset('assets/images/image/4+.jpg')}}" alt="">
				</div>
			</div>
			<div class="col-md-4">
				<div class="partner">
					<img src="{{asset('assets/images/image/5+.jpg')}}" alt="">
				</div>
			</div>
			<div class="col-md-4">
				<div class="partner">
					<img src="{{asset('assets/images/image/6+.jpg')}}" alt="">
				</div>
			</div>
		</div>
	</div>
</div> --}}
@endsection
@section('scripts')
@endsection