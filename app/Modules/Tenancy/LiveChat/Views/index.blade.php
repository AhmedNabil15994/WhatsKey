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
							<div class="flex-row-auto offcanvas-mobile w-400px w-xl-500px" id="kt_chat_aside">
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
		<script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script>
		<script src="{{asset('assets/tenant/js/pages/custom/chat/chat.js')}}"></script>
		<script src='https://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
		<script src="{{ asset('/assets/tenant/js/locationpicker.jquery.js') }}"></script>
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
			    	$('.sendMsg textarea').focus()
			    });

		    	$('#kt_scrollDown').on('click',function(){
			    	$('#kt_scrollDown').addClass('hidden')
			    	$('.scroll-pulls').scrollTop(1000000);
		    	});

			    $('.scroll-pulls').on('scroll',function(){
		    		if(this.scrollTop == 0) {
				    	window.livewire.emit('loadMoreMsgs')
				    }
				    $('#kt_scrollDown').removeClass('hidden')
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

			    window.livewire.on('showQuickReplyModal', () => {
			    	$('#quickReply input[name="reply"]').prop('checked',false)
			        $('#quickReply').modal('show');
			    });

			    $(document).on('click','.selectReply',function(e){
					e.preventDefault()
					var replyId=  $('#quickReply input[name="reply"]:checked').val()
					window.livewire.emitTo('send-msg','setReply',replyId,1)
			        $('#quickReply').modal('hide');
				});

				window.livewire.on('showTemplateModal', () => {
			    	$('#templateModal input[name="template"]').prop('checked',false)
			        $('#templateModal').modal('show');
			    });

				$(document).on('click','.selectTemplate',function(e){
					e.preventDefault()
					var replyId=  $('#templateModal input[name="template"]:checked').val()
					window.livewire.emitTo('send-msg','setReply',replyId,2)
			        $('#templateModal').modal('hide');
				});

				window.livewire.on('showContactModal', () => {
			    	$('#contactsModal select').val('')
					$('select[data-toggle="select2"]').select2()
			        $('#contactsModal').modal('show');
			    });

				$(document).on('click','.selectContact',function(e){
					e.preventDefault()
					var replyId=  $('#contactsModal select option:selected').val()
					window.livewire.emitTo('send-msg','setContact',replyId)
			        $('#contactsModal').modal('hide');
			        
				});

				window.livewire.on('showMapModal', () => {
			        $('#locationModal').modal('show');
				    $('#locationModal #somecomponent').locationpicker({
				        location: {
				            latitude:  21.5362381,
				            longitude: 39.1706268
				        },
				        onchanged: function (currentLocation, radius, isMarkerDropped) {
				            var addressComponents = $(this).locationpicker('map').location.addressComponents;
				            $('#locationModal .selectAddress').attr('data-lat',currentLocation.latitude);
				            $('#locationModal .selectAddress').attr('data-lng',currentLocation.longitude);
				        },
				    });
			    });

				$(document).on('click','.selectAddress',function(e){
					e.preventDefault()
					window.livewire.emitTo('send-msg','setLocation', $('#locationModal .selectAddress').data('lat'), $('#locationModal .selectAddress').data('lng'),$('#locationModal input[name="address"]').val())
			        $('#locationModal').modal('hide');
			        
				});

				Livewire.on('setMessageText', chat => {
			    	$('.sendMsg textarea').val(chat)
			    });

				Livewire.on('setMessageContact', chat => {
			    	$('.sendMsg textarea').val(chat)
					$('.sendMsg .btn-primary').trigger('click');
			    });

				Livewire.on('setMessageLocation', chat => {
			    	$('.sendMsg textarea').val(chat)
					$('.sendMsg .btn-primary').trigger('click');
			    });

			    Livewire.on('playAudio', chat => {
			    	new Audio("{{ asset('assets/tenant/swiftly1.mp3') }}").play();
			    });

			    Livewire.on('focusInput', chat => {
			    	$('.sendMsg textarea').focus()
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
					$('.msgFile')[0].click()
				});
		    })
		</script>
		<script src="{{mix('js/app.js')}}"></script>
	</body>
</html>