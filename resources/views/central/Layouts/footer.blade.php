<div class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<ul>
					<li><a href="{{URL::to('/')}}">الرئيسية</a></li>
					<li><a href="{{URL::to('/whoUs')}}">من نحن</a></li>
					<li><a href="{{URL::to('/')}}#advantages">مميزاتنا</a></li>
					<li><a href="{{URL::to('/')}}#packages">الباقات</a></li>
					<li><a href="{{URL::to('/contact')}}">اتصل بنا</a></li>
					<li><a href="{{URL::to('/faq')}}">الأسئلة الشائعة</a></li>
					<li><a href="{{URL::to('/privacy')}}">الشروط والأحكام</a></li>
					<li><a href="{{URL::to('/explaination')}}">الشرح</a></li>
					<div class="clearfix"></div>
				</ul>
			</div>
			<div class="col-md-6">
				<img src="{{asset('assets/images/noBackLogo.png')}}" alt="">
				<p>جميع الحقوق محفوظة &copy; Whatskey {{date('Y')}}</p>
			</div>
		</div>
	</div>
</div>