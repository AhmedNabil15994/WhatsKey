<div class="flex-row-fluid ml-lg-8" id="kt_chat_content">
    <div class="card card-custom" style="height: 100%; background: url({{asset('assets/tenant/images/bg-chat.png')}});">
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
                    @if($selected)
                    @foreach(array_reverse($messages) as $oneMessage)
                    @php 
                        $oneMessage = (array) $oneMessage; 
                        // dd($messages);
                        $oneMessage['body'] = mb_convert_encoding($oneMessage['body'], 'UTF-8', 'UTF-8');
                        $oneMessage['metadata'] = (array) $oneMessage['metadata']; 
                        $id = array_reverse(explode('_',$oneMessage['id']))[0];
                    @endphp
                    <livewire:message :wire:key="time().'myMsg'.$id" :msg="$oneMessage" :chatName="$chat['name']"/>               
                    @endforeach
                    @endif
                    <div class="clearfix"></div>
                </div>
            </div>
            <div id="kt_scrollDown" class="scrollDown text-right float-right hidden">
                <span class="fa-icon fa-icon-xl">
                    <i class="la la-angle-double-down text-white"></i>
                </span>
            </div>
        </div>
        <div class="card-footer px-3 py-3 align-items-center" style="background:{{$selected ? '#FFF':'transparent'}}">
        @if($selected)
        <livewire:send-msg :selected="$selected" :wire:key="time().'send'.$chat['id']"/>


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
        </div>
    </div>
</div>

          

