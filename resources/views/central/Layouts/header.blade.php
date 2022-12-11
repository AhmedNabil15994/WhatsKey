<nav class="navbar navbar-expand-lg">
	<div class="container">
	  	<a class="navbar-brand" href="{{URL::to('/')}}">
	  		<img src="{{asset('assets/images/whiteLogo.png')}}" alt="">
	  	</a>
	  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	    	<span class="navbar-toggler-icon"></span>
	  	</button>

	  	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		    <ul class="navbar-nav mr-auto">
		      	<li class="nav-item ">
		        	<a class="nav-link {{Active(URL::to('/'))}}" href="{{URL::to('/')}}">الرئيسية</a>
		      	</li>
		      	<li class="nav-item">
		        	<a class="nav-link {{Active(URL::to('/whoUs'))}}" href="{{URL::to('/whoUs')}}">من نحن</a>
		      	</li>
		      	<li class="nav-item">
		        	<a class="nav-link" href="{{URL::to('/')}}#advantages">مميزاتنا</a>
		      	</li>
		      	<li class="nav-item">
		        	<a class="nav-link" href="{{URL::to('/')}}#packages">الباقات</a>
		      	</li>
		      	<li class="nav-item">
		        	<a class="nav-link {{Active(URL::to('/contactUs'))}}" href="{{URL::to('/contactUs')}}">اتصل بنا</a>
		      	</li>
		    </ul>
			<div class="form-inline">
			    <a href="{{URL::to('/login')}}" class="btn loginBtn" type="submit">تسجيل الدخول</a>
			</div>
	  	</div>
  	</div>
</nav>