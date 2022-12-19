<!DOCTYPE html>
<html lang="{{ LANGUAGE_PREF }}" dir="{{ DIRECTION }}">
	<head>
		<meta charset="utf-8" />
		<title>واتس كي | WhatsKey | {{trans('main.livechat')}}</title>
		<meta name="description" content="#" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		@yield('extra-metas')
		@include('tenant.Layouts.head')
	</head>
	<!--end::Head-->
	
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed">
		@include('tenant.Layouts.mobileHeader')
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-row flex-column-fluid page">
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
					@include('tenant.Layouts.header')
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
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
						@section('pageName',trans('main.livechat'))
						<div class="d-flex flex-row px-5">
							<div class="flex-row-auto offcanvas-mobile w-350px w-xl-400px" id="kt_chat_aside">
								<div class="card card-custom">
									<div class="card-body px-3 bg-gray-100" style="padding-top: .5rem; border-radius:5px;">
										<livewire:search-chats :wire:key="searchChats"/>
										<div class="mt-2 scroll scroll-pull">
											<livewire:chats :wire:key="viewChats"/>
						    			</div>
									</div>
								</div>
							</div>

							<livewire:conversation :wire:key="viewConversation"/>
						</div>
					</div>
				</div>
			</div>
		</div>

		@include('tenant.Layouts.scripts')
		@include('tenant.Partials.notf_messages')
		@section('scripts')
		<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
		<script src="{{asset('assets/tenant/js/pages/custom/chat/chat.js')}}"></script>
		<script>
		    $(function(){
				
				window.livewire.emit('loadDialogs')
		    	$('#kt_chat_aside .scroll').on('scroll',function(){
		    		if(this.scrollTop == (this.scrollHeight - this.offsetHeight)) {
				      	$('.spinContainer').removeClass('hidden')
				    	window.livewire.emit('loadMore')
				    }
		    	});

		    	Livewire.on('conversationOpened', chat => {
			    	$('#kt_scrollDown').click()
			    	$('[data-toggle="tooltip"]').tooltip()
			    	$('#kt_scrollDown').addClass('hidden')
			    });

			    $(document).on('click','.emoji',function(e){
			    	e.preventDefault();
			    	$('emoji-picker').toggleClass('hidden')
			    	document.querySelector('emoji-picker').addEventListener('emoji-click', event => $('.sendTextArea').val($('.sendTextArea').val() + event.detail.unicode));
			    });

		    	$('#kt_scrollDown').on('click',function(){
			    	$('#kt_scrollDown').addClass('hidden')
			    	$('.scroll-pulls').scrollTop(1000000);
		    	});

		    	$(document).on('click','.sendTextArea,a.btn-clean:not(.emoji),.btn-primary,.card-body',function(){
		    		if(!$('emoji-picker').hasClass('hidden')){
						$('emoji-picker').addClass('hidden')
		    		}
		    	})

			    $('.scroll-pulls').on('scroll',function(){
		    		if(this.scrollTop == 0) {
				    	window.livewire.emit('loadMoreMsgs')
				    }
				    $('#kt_scrollDown').removeClass('hidden')
		    	});


		    	$(document).ready(function () {
			        window.livewire.emit('showModal');
			    });

			    window.livewire.on('showModal', () => {
			        $('#listSections').modal('show');
			    });

			    $(document).on('change','[name="radios"]',function(){
			    	if($(this).is(':checked')){
			    		$('p.score').html(0);
			    		$('.progress-bar').data('aria-valuenow',0);
			    		$('.progress-bar').css('width','0%');
			    		$(this).parents('.float-left').siblings('div.float-right').children('p.score').html(1);
			    		$(this).parents('.w-100').find('.progress-bar').data('aria-valuenow',100);
			    		$(this).parents('.w-100').find('.progress-bar').css('width','100%');
			    	}
			    });

			    Livewire.on('playAudio', chat => {
			    	new Audio("{{ asset('assets/tenant/swiftly1.mp3') }}").play();
			    });

			    $(document).on('keydown','.sendMsg textarea', function(e) {
					if (e.keyCode == 13) {
						e.preventDefault();
						window.livewire.emitTo('send-msg','sendMsg',$(this).val())
						$(this).val(' ')
					}
				});

				$(document).on('click','.sendMsg .btn-primary', function(e) {
					let myEvent = $.Event('keydown');
					myEvent.keyCode = 13;
					$('.sendMsg textarea').trigger(myEvent)
				});				

				$(document).on('click','.replyMsg',function(e){
					let id = $(this).data('id');
			    	$('.scroll-pulls').scrollTop($(this).parents('.messages').find('#'+id)[0].offsetTop);
				});

				$(document).on('click','.attachment',function(e){
					$('input[type="file"]')[0].click()
				});


		      
		    })
		</script>
		<script src="{{mix('js/app.js')}}"></script>
	</body>
</html>