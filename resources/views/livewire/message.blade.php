<div class="msgD {{$msg['fromMe'] == 1 ? 'left':'right'}}">
    @php 
        $msg = (array) $msg; 
        $id = array_reverse(explode('_',$msg['id']))[0];
    @endphp

    @if($msg['message_type'] != 'call')
    <div class="messageItem d-flex flex-column align-items-{{$msg['fromMe'] == 1 ? 'end' : 'start'}}" id="{{$id}}">
        <div class="mt-2 rounded p-3 bg-{{$msg['fromMe'] == 1 ? 'white' : 'light-success'}} text-dark-50 font-weight-bold font-size-lg text-{{$msg['fromMe'] == 1 ? 'right' : 'left'}} max-w-400px" style="position:relative; {{ $msg['message_type'] == 'disappearing' ? 'position: relative;' : ''}}">

            @if($msg['deleted_at'] != null || $msg['sending_status'] == 6)
            <livewire:messages.deleted-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>
            @else
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline">
                        <a href="#" class="btn btn-hover-light-primary btn-xs btn-icon {{$msg['fromMe'] != 1 ? 'bg-gray-100' : ''}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="la la-angle-down"></i>
                        </a>
                        <div class="dropdown-menu p-0 m-0 dropdown-menu-sm dropdown-menu-{{$msg['fromMe'] == 1 ? 'left':'right'}}" dir="ltr">
                            <ul class="navi navi-hover">
                                <li class="navi-item replyItem" data-id="{{$id}}" data-name="{{$chatName}}">
                                    <a href="#" class="navi-link p-2">
                                        <span class="text-dark">Reply <i class="la la-reply icon-md"></i></span>
                                    </a>
                                </li>
                                <li class="navi-item forwardItem" data-id="{{$id}}">
                                    <a href="#" class="navi-link p-2">
                                        <span class="text-muted">
                                            <svg viewBox="0 0 16 16" height="16" width="16" preserveAspectRatio="xMidYMid meet" class="" version="1.1"><path d="M9.51866667,3.87533333 C9.51866667,3.39333333 10.1006667,3.152 10.4406667,3.49266667 L14.4706667,7.52666667 C14.682,7.738 14.682,8.07933333 14.4706667,8.29066667 L10.4406667,12.3246667 C10.1006667,12.6646667 9.51866667,12.424 9.51866667,11.942 L9.51866667,10.1206667 C6.12133333,10.1206667 3.63266667,11.0906667 1.78266667,13.1946667 C1.61866667,13.3806667 1.31466667,13.2226667 1.38133333,12.984 C2.33466667,9.53533333 4.66466667,6.31466667 9.51866667,5.62066667 L9.51866667,3.87533333 Z" fill="currentColor"></path></svg>
                                        </span>
                                        <span class="text-dark">Forward</span>
                                    </a>
                                </li>
                                <li class="navi-item deleteForMeItem" data-id="{{$id}}">
                                    <a href="#" class="navi-link p-2">
                                        @if($msg['deleted_by'] == null)
                                            <span class="text-dark">Delete For Me <i class="la la-trash icon-md"></i></span>
                                        @elseif($msg['deleted_by'] == Session::get('user_id') && Session::get('is_admin') == 1)
                                            <span class="text-dark">Undo <i class="la la-undo-alt icon-md"></i></span>
                                        @endif
                                    </a>
                                </li>
                                @if($msg['fromMe'])
                                <li class="navi-item deleteForAllItem" data-id="{{$id}}">
                                    <a href="#" class="navi-link p-2">
                                        <span class="text-dark">Delete For Everyone <i class="la la-trash icon-md"></i> </span>
                                    </a>
                                </li>
                                @endif

                                @if(\Session::get('BUSINESS') == 1)
                                <li class="navi-item labelItem" data-id="{{$id}}" data-labels="{{isset($msg['labels']) && $msg['labels'] != ',' ? $msg['labels'] : ''}}">
                                    <a href="#" class="navi-link p-2">
                                        <span class="text-dark">Label <i class="la la-tag icon-md"></i> </span>
                                    </a>
                                </li>
                                @endif

                                <li class="navi-item starItem" data-id="{{$id}}">
                                    <a href="#" class="navi-link p-2">
                                        <span class="text-dark">{{$msg['starred'] ? 'UnStar' : 'Star'}} <i class="la la-star icon-md"></i> </span>
                                    </a>
                                </li>
                                <li class="navi-item resendItem" onclick="Livewire.emitTo('send-msg','repeatHook','{{$msg['id']}}')">
                                    <a href="#" class="navi-link p-2">
                                        <span class="text-dark">Resend <i class="la la-redo-alt icon-md"></i> </span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @if(str_contains($msg['chatId'], '@g.us'))
                <span class="text-muted mb-2 d-block">{{str_replace('@c.us','',$msg['author'])}}</span>
                @endif
                <livewire:message-reply :wire:key="'msgReply'.time().$id" :msg="$msg" :chatName="$chatName"/>
                @if($msg['message_type'] == 'text')
                <livewire:messages.text-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'image')
                <livewire:messages.image-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'video')
                <livewire:messages.video-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'audio')
                <livewire:messages.audio-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'document')
                <livewire:messages.file-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'sticker')
                <livewire:messages.sticker-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'gif')
                <livewire:messages.gif-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'linkWithPreview')
                <livewire:messages.link-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'location')
                <livewire:messages.location-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'contact')
                <livewire:messages.contact-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'disappearing')
                <livewire:messages.disappearing-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'mention')
                <livewire:messages.mention-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'buttons')
                <livewire:messages.buttons-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'template')
                <livewire:messages.template-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'list')
                <livewire:messages.list-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>
                <livewire:messages.list-sections :msg="$msg" :wire:key="'modal'.time().$id"/>

                @elseif($msg['message_type'] == 'groupInvitation')
                <livewire:messages.group-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'catalog')
                <livewire:messages.catalog-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'product')
                <livewire:messages.product-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'order')
                <livewire:messages.order-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'poll')
                <livewire:messages.poll-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'buttons_response')
                <livewire:messages.btn-response-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'list_response')
                <livewire:messages.list-response-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @elseif($msg['message_type'] == 'poll_vote' || $msg['message_type'] == 'poll_unvote')
                <livewire:messages.poll-response-msg :msg="$msg" :wire:key="'msg'.time().$id" :chatName="$chatName"/>

                @endif
            @endif
            <livewire:message-details :wire:key="'msgDetails'.time().$id" :msg="$msg" :chatName="$chatName" :noMargin="1"/>

            @if(count($msg['reactions']) > 0 && !empty($msg['reactions']) && isset($msg['reactions'][0]['id']) && $msg['reactions'][0]['body'] != null)
            <div class="reactions">
                @foreach($msg['reactions'] as $oneReaction)
                @if(!empty($oneReaction) && isset($oneReaction['fromMe']) && $oneReaction['body'] != '')
                <div class="reaction text-center pt-2" data-toggle="tooltip" data-original-title="{{ $oneReaction['fromMe'] ? trans('main.you') : $chatName}}">
                    <span class="fa-icon"> {{$oneReaction['body']}} </span>
                </div>
                @endif
                @endforeach
            </div>
            @endif

            <div class="msgEmoji">
                <a href="#" class="btn btn-secondary btn-icon btn-sm emojiItem">
                    <i class="far fa-smile icon-md text-muted"></i>
                </a>
                {{-- <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker> --}}
            </div>
        </div>
    </div>
    @else
    <div class="row m-0 p-0 text-center">
        <div class="call bg-white border-radius-5 text-center p-3 d-block w-auto m-auto">
            @if($msg['metadata']['isVideo'] == true)
            <span class="svg-icon text-danger">
                <svg viewBox="0 0 19 16" height="16" width="19" preserveAspectRatio="xMidYMid meet" class="" version="1.1" x="0px" y="0px" enable-background="new 0 0 19 16" xml:space="preserve"><path fill="currentColor" d="M11.2,1.8H3.3C2,1.8,1,2.8,1,4.1V11c0,1.1,1,2.2,2.3,2.2h8c1.2,0,2.3-1,2.3-2.3V4.1 C13.5,2.8,12.4,1.8,11.2,1.8z M10.3,5.3L6.4,9.2h2.1c0.2,0,0.4,0.2,0.4,0.4V10c0,0.2-0.2,0.4-0.4,0.4H4.7c-0.2,0-0.4-0.2-0.4-0.4 V6.2c0-0.2,0.2-0.4,0.4-0.4h0.4c0.2,0,0.4,0.2,0.4,0.4v2.1l3.9-3.9c0.2-0.2,0.4-0.1,0.5,0l0.3,0.3C10.4,4.9,10.4,5.1,10.3,5.3z  M15.1,5.3c-0.2,0.1-0.3,0.3-0.3,0.5v3.3c0,0.2,0.1,0.4,0.3,0.5l2.9,2.1V3.2L15.1,5.3z"></path></svg>
            </span>
            <span dir="rtl">{{trans('main.missedVideo')}} {{$msg['date_time']}}</span>
            @else
            <span class="svg-icon text-danger">
                <svg viewBox="0 0 20 17" height="17" width="20" preserveAspectRatio="xMidYMid meet" class="" version="1.1" x="0px" y="0px" enable-background="new 0 0 20 17" xml:space="preserve"><path fill="currentColor" d="M18.2,12.1L18.2,12.1c-1.5-1.8-5-2.7-8.2-2.7s-6.7,1-8.2,2.7c-0.7,0.8-0.3,2.3,0.2,2.8 c0.2,0.2,0.3,0.3,0.5,0.3c1.4,0,3.6-0.7,3.6-0.7c0.5-0.2,0.8-0.5,0.8-1c0,0,0-0.7,0-1.3c0.7-1.2,5.4-1.2,6.4-0.1c0,0,0,0,0.1,0.1 v1.3c0,0.2,0.1,0.4,0.2,0.6c0.1,0.2,0.3,0.3,0.5,0.4c0,0,2.2,0.7,3.6,0.7C17.9,15.2,19.1,13.2,18.2,12.1z M5.4,3.2l4.7,4.6l5.8-5.7 L15,1.3L10.1,6L6.4,2.3h2.5V1H4.1v4.8h1.3C5.4,5.8,5.4,3.2,5.4,3.2z"></path></svg>
            </span>
            <span dir="rtl">{{trans('main.missedVoice')}} {{$msg['date_time']}}</span>
            @endif
        </div>
    </div>
    @endif
    @if($msg['fromMe'])
    <div class="text-dark text-right font-weight-bolder mb-5 mt-2 bg-gray-100 px-2 pt-1 w-fit float-right" style="z-index: 99999999;position: relative;width: fit-content;border-radius: 5px">{{$msg['status']}}</div>    
    @endif
    <div class="clearfix"></div>
</div>
