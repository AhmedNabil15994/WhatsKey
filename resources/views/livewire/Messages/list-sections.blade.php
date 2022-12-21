<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
	<div class="modal fade" id="listSections">
		<div class="modal-dialog modal-lg" role="document">
		    <div class="modal-content">
		        <div class="modal-header">
		        	<h5 class="modal-title">{{$msg['metadata']['buttonText']}}</h5>
		            <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
		                <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
		            </button>
		        </div>
		        <div class="modal-body px-10 py-10">
		        	@foreach($msg['metadata']['sections'] as $sectionKey => $section)
		        	@php $section = (array) $section; @endphp
		        	<div class="row mb-5">
		        		<h5 class="modal-title mb-5"> {{$section['title']}} </h5>
		        		<div class="radio-list w-100">
		        		@foreach($section['rows'] as $rowKey => $oneRow)
		        		<div class="w-100 mb-3">
		        			<div class="float-left">
		        				<label class="radio radio-outline radio-success">
				                    <input type="radio" value="{{$oneRow['id']}}"  name="radios{{$sectionKey}}"/>
				                    <span></span>
				                </label>
		        			</div>
		        			<div class="float-left text-left">
		        				<p>{{$oneRow['title']}}</p>
		        				<p>{{$oneRow['description']}}</p>
		        			</div>
		        		</div>
		        		@endforeach
		        		</div>
		        	</div>
		        	@endforeach
		        </div>
		        <div class="modal-footer">
		        	<button class="btn btn-success">{{trans('main.save')}}</button>
		        </div>
		    </div>
		</div>
	</div>
</div>