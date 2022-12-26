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
										<div class="newMessage p-5 bg-white">
											<h4 class="card-title"> <i class="la la-envelope-open icon-xl"></i> {{trans('main.newMessage')}}</h4>
											<div class="form-group">
												<label>{{trans('main.replyType')}}</label>
												<select name="message_type" class="form-control" data-toggle="select2">
													<option value="1">{{trans('main.singleMsg')}}</option>
													<option value="2">{{trans('main.groupMsg')}}</option>
												</select>
											</div>
											<div data-select="1">
												<div class="form-group">
				                                    <label> {{ trans('main.phone') }}</label>
				                                    <input type="hidden" name="phone">
				                                    <input type="tel" id="telephone" class="form-control" placeholder="{{ trans('main.phone') }}">
				                                </div>
											</div>
											<div data-select="2">
												<div class="form-group">
													<label>{{trans('main.numbers')}}</label>
													<select name="types" class="form-control" data-toggle="select2">
														<option value="">{{trans('main.choose')}}</option>
														<option value="1">{{trans('main.contacts')}}</option>
														<option value="2">{{trans('main.newContacts')}}</option>
													</select>
												</div>
												<div class="form-group" data-id="1">
													<label>{{trans('main.numbers')}}</label>
													<select name="newContacts[]" class="form-control" data-toggle="select2" multiple>
														@foreach($data->contacts as $contact)
														<option value="{{ str_replace('+','',$contact->phone) }}">{{ $contact->name }}</option>
														@endforeach
													</select>
												</div>
												<div class="form-group" data-id="2">
													<label>{{trans('main.numbers')}}</label>
	                            					<textarea class="form-control" name="newPhones" placeholder="{{ trans('main.whatsappNos2') }}"></textarea>
												</div>
											</div>
											<div class="form-group textWrap">
												<label>{{trans('main.message')}}</label>
												<textarea class="form-control" rows="2" name="message" placeholder="Type a message"></textarea>
												<i class="la la-smile icon-xl newMsg-icon"></i>
												<emoji-picker class="hidden newMSG" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
											</div>	
											<div class="w-100 text-right">
												<button type="button" class="btn btn-primary mr-2 addNewMessage">{{trans('main.send')}}</button>
                								<a href="#" class="btn btn-secondary closeNewMessage">{{trans('main.back')}}</a>
											</div>	
										</div>
										<div class="newGroup p-5 bg-white">
											<h4 class="card-title"><i class="la la-users icon-xl"></i> {{trans('main.newGroup')}}</h4>
											<div class="form-group textWrap">
												<label>{{trans('main.name')}}</label>
												<input type="text" name="groupName" class="form-control" placeholder="{{trans('main.name')}}">
												<i class="la la-smile icon-xl newGroup-icon"></i>
												<emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
											</div>
											<div class="form-group">
												<label>{{trans('main.numbers')}}</label>
												<select name="numbers" class="form-control" data-toggle="select2">
													<option value="1">{{trans('main.onlyMe')}}</option>
													<option value="2">{{trans('main.contacts')}}</option>
													<option value="3">{{trans('main.newContacts')}}</option>
												</select>
											</div>
											<div class="form-group" data-id="2">
												<label>{{trans('main.numbers')}}</label>
												<select name="contactsPhone[]" class="form-control" data-toggle="select2" multiple>
													@foreach($data->contacts as $contact)
													<option value="{{ str_replace('+','',$contact->phone) }}">{{ $contact->name }}</option>
													@endforeach
												</select>
											</div>
											<div class="form-group" data-id="3">
												<label>{{trans('main.numbers')}}</label>
                            					<textarea class="form-control" name="phones" placeholder="{{ trans('main.whatsappNos2') }}"></textarea>
											</div>
											<div class="w-100 text-right">
												<button type="button" class="btn btn-primary mr-2 addNewWAGroup">{{trans('main.add')}}</button>
                								<a href="#" class="btn btn-secondary closeNewGroup">{{trans('main.back')}}</a>
											</div>	
										</div>
										<div class="allChats">
											<livewire:search-chats :wire:key="searchChats"/>
											<div class="mt-2 scroll scroll-pull">
												<livewire:chats :wire:key="viewChats"/>
							    			</div>
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
		<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
		<script src="{{asset('assets/tenant/js/recorder.js')}}"></script>
		<script src="{{asset('assets/tenant/js/pages/custom/chat/chat.js')}}"></script>
		<script src='{{asset('assets/tenant/js/gmaps.js')}}'></script>
		<script src="{{asset('/assets/tenant/js/locationpicker.jquery.js')}}"></script>
		<script src="{{asset('assets/tenant/components/livechat.js')}}"></script>
		<script src="{{mix('js/app.js')}}"></script>
	</body>
</html>