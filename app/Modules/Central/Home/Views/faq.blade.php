@extends('central.Layouts.master')
@section('title','الأسئلة الشائعة')
@section('content')
<div class="firstSection">
	<div class="container">
		<div class="row">
			<div class="col-md-6">
				<h2>الأسئلة الشائعة</h2>
				<p>الرئيسية <i class="fa fa-angle-left"></i> الأسئلة الشائعة</p>
			</div>
			<div class="col-md-6">
				<img src="{{asset('assets/images/Illustration/undraw_questions_re_1fy7.svg')}}" alt="">
			</div>
		</div>
	</div>
</div>


<div class="faqs" id="dataSection">
	<div class="container">
		<div class="row">
			<h2>الأسئلة الشائعة</h2>
			@foreach($data->faq as $one)
			<div class="faqItem">
			  	<div class="card">
			    	<div class="card-header">
			      		<h5 class="mb-0">
			        		<a class="card-title">{{$one->title}}</a>
			        		<i class="float-left fa fa-plus"></i>
			        		<div class="clearfix"></div>
			      		</h5>
			    	</div>
			    	<div class="collapse">
			      		<div class="card-body">{{$one->description}}</div>
			    	</div>
			  	</div>
			</div>
			@endforeach
		</div>
	</div>
</div>
@endsection
@section('scripts')
@endsection