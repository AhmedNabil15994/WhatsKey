@extends('tenant.Layouts.master')
@section('title',trans('main.livechat'))
@section('pageName',trans('main.livechat'))

@section('styles')
@endsection

@section('breadcrumbs')
@include('tenant.Layouts.breadcrumb',[
    'breadcrumbs' => [
        [
            'title' => trans('main.menu'),
            'url' => \URL::to('/dashboard')
        ],
        [
            'title' => trans('main.livechat'),
            'url' => \URL::to('/livechat')
        ],
    ]
])
@endsection

@section('content')
<div class="d-flex flex-row">
	<div class="flex-row-auto offcanvas-mobile w-350px w-xl-400px" id="kt_chat_aside">
		<div class="card card-custom">
			<div class="card-body">
				<livewire:search-chats />
				<div class="mt-7 scroll scroll-pull">
					<livewire:chats/>
    			</div>
			</div>
		</div>
	</div>

	<livewire:conversation />
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/tenant/js/pages/custom/chat/chat.js')}}"></script>
<script>
    
    // Livewire.on('conversationClosed', chat => {
    //     $('#kt_chat_content').addClass('hidden')
    // })
    $(function(){
		
		window.livewire.emit('loadDialogs')
    	
    	$('#kt_chat_aside .scroll').on('scroll',function(){
    		if(this.scrollTop == (this.scrollHeight - this.offsetHeight)) {
		      	$('.spinContainer').removeClass('hidden')
		    	window.livewire.emit('loadMore')
		    }
    	});

    	Livewire.on('conversationOpened', chat => {
	    	$('.scroll-pulls').scrollTop($('.messages')[0].scrollHeight);
	    });


    	// $('.messages').on('scroll',function(){
    	// 	if(this.scrollTop == $('.messages')[0].scrollHeight - 200) {
	    // 		$('#kt_scrollDown').removeClass('hidden')
		//     	// window.livewire.emit('loadMoreMsgs')
		//     }
    	// });

    	$('#kt_scrollDown').on('click',function(){
	    	$('#kt_scrollDown').addClass('hidden')
	    	$('.scroll-pulls').scrollTop($('.messages')[0].scrollHeight);
    	});

	    $('.scroll-pulls').on('scroll',function(){
    		if(this.scrollTop == 0) {
		    	window.livewire.emit('loadMoreMsgs')
	    		$('#kt_scrollDown').removeClass('hidden')
		    }else if(this.scrollTop > 0) {
	    		$('#kt_scrollDown').removeClass('hidden')
		    }
    	});
    })
</script>

@endsection
