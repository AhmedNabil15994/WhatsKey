<div class="flex-row-fluid ml-lg-8" id="kt_chat_content">
    <div class="card card-custom" style="height: 100%; background: url({{ ($selected && $chat['background'] ? $chat['background'] : asset('assets/tenant/images/bg-chat.png'))}});">
        <div class="card-header px-1 py-1" style="display: block;min-height: 45px;background:{{$selected ? '#FFF':'transparent'}}"> 
        @if($selected)
        @php
            $chat['name'] = mb_convert_encoding($chat['name'], 'UTF-8', 'UTF-8');
        @endphp
        <livewire:chat-actions  :name="$chat['name']" :wire:key="time().'actions'.$chat['id']" />
        @endif
        </div>
        <div class="card-body" style="position:relative;padding: 0 1.5rem; {{$selected ? 'background: rgb(156 167 96 / 25%)' : ''}};">
            <div class="scroll scroll-pull scroll-pulls" data-mobile-height="350">
                <div class="messages" id="messages">
                    <div class="spinMsgContainer py-3 text-center hidden">
                        <div class="spinner spinner-track spinner-lg spinner-success mr-15 text-center" style="display: initial;"></div>
                    </div>
                    <div>
                    @if($selected)
                    @foreach(array_reverse($messages) as $oneMessage)
                    @php 
                        $oneMessage = (array) $oneMessage; 
                        // dd($messages);
                        $oneMessage['body'] = mb_convert_encoding($oneMessage['body'], 'UTF-8', 'UTF-8');
                        $oneMessage['metadata'] = (array) $oneMessage['metadata']; 
                        $id = array_reverse(explode('_',$oneMessage['id']))[0];
                    @endphp
                    <livewire:message :wire:key="time().$oneMessage['message_type'].$id" :msg="$oneMessage" :chatName="$chat['name']"/>               
                    @endforeach
                    @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="kt_scrollDown" class="scrollDown text-right float-right {{ !$selected ? 'hidden' : ''}}">
                <span class="fa-icon fa-icon-xl">
                    <i class="la la-angle-double-down text-white"></i>
                </span>
            </div>
        </div>
        @if($selected)
        <div class="msgReplyHeader p-5 bg-gray-100" style="display:none;">
            <div class="row m-0 p-0">
                <span class="fa-icon d-inline-block closeReplyHeader" style="cursor: pointer;">
                    <i class="la la-times-circle icon-xl"></i>
                </span>
                <span class="text-dark font-weight-bolder text-left mx-2 d-inline-block replyName" dir="ltr">
                    {{$selected}}
                </span>
                <span class="text-dark d-block mx-9 w-100 replyBody" style="margin-top: -5px;"></span>
            </div>
        </div>
        @endif
        <div class="card-footer p-3 align-items-center" style="background:{{$selected ? '#FFF':'transparent'}}">
            @if($selected)
            <div>
                @if($chat['blocked'] == 0)
                <livewire:send-msg :selected="$selected" :wire:key="time().'send'.$chat['id']"/>
                @else
                <div class="text-center text-dark-50 p-5 bg-gray-100">Can't send a message to blocked Contact.</div>
                @endif
            </div>

            <div class="modal fade" id="quickReply">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('main.quickReplies')}}</h5>
                            <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
                                <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
                            </button>
                        </div>
                        <div class="modal-body px-10 py-10">
                            @foreach($replies as $replyKey => $reply)
                            <div class="row">
                                <div class="radio-list w-100">
                                    <div class="w-100 mb-3">
                                        <div class="float-left">
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" value="{{$reply['id']}}"  name="reply"/>
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="float-left text-left">
                                            <p class="text-dark">{{$reply['title']}}</p>
                                            <p class="text-muted">{{$reply['description']}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success selectReply">{{trans('main.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="templateModal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('main.templates')}}</h5>
                            <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
                                <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
                            </button>
                        </div>
                        <div class="modal-body px-10 py-10">
                            @foreach($templates as $templateKey => $template)
                            <div class="row">
                                <div class="radio-list w-100">
                                    <div class="w-100 mb-3">
                                        <div class="float-left">
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" value="{{$template['id']}}"  name="template"/>
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="float-left text-left">
                                            <p class="text-dark">{{$template['title']}}</p>
                                            <p class="text-muted">{{$template['description']}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success selectTemplate">{{trans('main.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="contactsModal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('main.contacts')}}</h5>
                            <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
                                <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
                            </button>
                        </div>
                        <div class="modal-body px-10 py-10">
                            <div class="form-group">
                                <label>{{trans('main.contact')}}</label>
                                <select name="contact" class="form-control" data-toggle="select2">
                                    <option value="">{{ trans('main.choose') }}</option>
                                    @foreach($contacts as $contact)
                                    <option value="{{ $contact['id'] }}">{{ $contact['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success selectContact">{{trans('main.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="forwardModal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('main.contacts')}}</h5>
                            <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
                                <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
                            </button>
                        </div>
                        <div class="modal-body px-10 py-10">
                            <div class="form-group">
                                <label>{{trans('main.contact')}}</label>
                                <select name="contact" class="form-control" data-toggle="select2">
                                    <option value="">{{ trans('main.choose') }}</option>
                                    @foreach($contacts as $contact)
                                    <option value="{{ str_replace('+','',$contact['phone']) }}">{{ $contact['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success forwardMsg">{{trans('main.forward')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="locationModal" data-lat="" data-lng="">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('main.location')}}</h5>
                            <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
                                <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
                            </button>
                        </div>
                        <div class="modal-body px-10 py-10">
                            <div id="somecomponent" style="width: 100%; height: 400px;"></div>
                            <div class="form-group mt-3">
                                <label>{{trans('main.address')}}</label>
                                <input type="text" class="form-control" name="address" placeholder="{{trans('main.address')}}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success selectAddress">{{trans('main.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="modal fade" id="labelsModal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('main.categories')}}</h5>
                            <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
                                <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
                            </button>
                        </div>
                        <div class="modal-body px-10 py-10">
                            <div class="form-group">
                                <label>{{trans('main.category')}}</label>
                                <select name="label[]" class="form-control" data-toggle="select2" multiple>
                                    @foreach($labels as $label)
                                    @php $label = (array) $label; @endphp
                                    <option value="{{ $label['labelId'] }}">{{ $label['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success selectLabels">{{trans('main.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="muteModal">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{trans('main.muteChat')}}</h5>
                            <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
                                <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
                            </button>
                        </div>
                        <div class="modal-body px-10 py-10">
                            <div class="row">
                                <div class="radio-list w-100">
                                    <div class="w-100 mb-3">
                                        <div class="float-left">
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" value="1"  name="duration"/>
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="float-left text-left">
                                            <p class="text-dark">يوم</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="radio-list w-100">
                                    <div class="w-100 mb-3">
                                        <div class="float-left">
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" value="7"  name="duration"/>
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="float-left text-left">
                                            <p class="text-dark">اسبوع</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="radio-list w-100">
                                    <div class="w-100 mb-3">
                                        <div class="float-left">
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" value="30"  name="duration"/>
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="float-left text-left">
                                            <p class="text-dark">شهر</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="radio-list w-100">
                                    <div class="w-100 mb-3">
                                        <div class="float-left">
                                            <label class="radio radio-outline radio-success">
                                                <input type="radio" value="365"  name="duration"/>
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="float-left text-left">
                                            <p class="text-dark">سنة</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success selectDuration">{{trans('main.save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uppy">
                <div class="d-flex uppy-thumbnails hidden">
                    <div class="uppy-thumbnail-container w-300px">
                        <div class="uppy-thumbnail"></div> 
                        <span class="uppy-thumbnail-label"></span>
                        <span class="uppy-remove-thumbnail"><i class="flaticon2-cancel-music"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>