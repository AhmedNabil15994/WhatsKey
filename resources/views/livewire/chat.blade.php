<div>
    @php
        $chat = json_decode(json_encode($chat), true);
        $spanIcon = '';
        $msgIcon = '';
        if(isset($chat['lastMessage'])){
            if($chat['lastMessage']['fromMe']){
                if($chat['lastMessage']['sending_status'] == 1){
                    $spanIcon = '<i class="icon-md la la-check"></i>';
                }elseif($chat['lastMessage']['sending_status'] == 2){
                    $spanIcon = '<i class="icon-md la la-check-double"></i>';   
                }elseif($chat['lastMessage']['sending_status'] == 3){
                    $spanIcon = '<i class="icon-md la la-check-double text-primary"></i>';    
                }elseif($chat['lastMessage']['sending_status'] == 0){
                    $spanIcon = '<i class="icon-md la la-clock"></i>';
                }
            }

            $senderText = $chat['lastMessage']['fromMe'] ? ('<span class="fa-icon">'. $spanIcon .'</span>') : '';
            $msgBody = mb_strlen($chat['lastMessage']['body']) > 85 ? mb_substr($chat['lastMessage']['body'], 0, 85) . ' ........' : $chat['lastMessage']['body'];

            if($chat['lastMessage']['deleted_at'] != null || $chat['lastMessage']['sending_status'] == 6){
                $msgBody = trans('main.deletedMsg');
                $msgIcon = '<span class="fa-icon"><i class="icon-md la la-ban"></i></span>';
            }else{
                if($chat['lastMessage']['message_type'] == 'image'){
                    $msgBody = $chat['lastMessage']['caption'] != '' ? (mb_strlen($chat['lastMessage']['caption']) > 85 ? mb_substr($chat['lastMessage']['caption'], 0, 85) . ' ........' : $chat['lastMessage']['caption']) : trans('main.pic');
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-camera"></i></span>';
                }else if($chat['lastMessage']['message_type'] == 'video'){
                    $msgBody = $chat['lastMessage']['caption'] != '' ? (mb_strlen($chat['lastMessage']['caption']) > 85 ? mb_substr($chat['lastMessage']['caption'], 0, 85) . ' ........' : $chat['lastMessage']['caption']) : trans('main.video');
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-video"></i></span>';
                }else if($chat['lastMessage']['message_type'] == 'audio'){
                    $msgBody = trans('main.audio');
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-headphones"></i></span>';
                }else if($chat['lastMessage']['message_type'] == 'document'){
                    $msgBody = mb_strlen($chat['lastMessage']['file_name']) > 85 ? mb_substr($chat['lastMessage']['file_name'], 0, 85) . ' ........' : $chat['lastMessage']['file_name'];
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-file-text"></i></span>';
                }else if($chat['lastMessage']['message_type'] == 'location'){
                    $msgBody = $chat['lastMessage']['body'] != '' ? (mb_strlen($chat['lastMessage']['body']) > 85 ? mb_substr($chat['lastMessage']['body'], 0, 85) . ' ........' : $chat['lastMessage']['body']) : trans('main.location');
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-map-marker-alt"></i></span>';
                }else if($chat['lastMessage']['message_type'] == 'sticker'){
                    $msgBody = trans('main.sticker');
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-sticky-note"></i></span>';
                }else if($chat['lastMessage']['message_type'] == 'gif'){
                    $msgBody = $chat['lastMessage']['caption'] != '' ? (mb_strlen($chat['lastMessage']['caption']) > 85 ? mb_substr($chat['lastMessage']['caption'], 0, 85) . ' ........' : $chat['lastMessage']['caption']) : trans('main.gif');
                    $msgIcon = '<span class="fa-icon"><svg viewBox="0 0 20 20" width="20" height="20" class=""><path fill="currentColor" d="M4.878 3.9h10.285c1.334 0 1.818.139 2.306.4s.871.644 1.131 1.131c.261.488.4.972.4 2.306v4.351c0 1.334-.139 1.818-.4 2.306a2.717 2.717 0 0 1-1.131 1.131c-.488.261-.972.4-2.306.4H4.878c-1.334 0-1.818-.139-2.306-.4s-.871-.644-1.131-1.131-.4-.972-.4-2.306V7.737c0-1.334.139-1.818.4-2.306s.643-.87 1.131-1.131.972-.4 2.306-.4zm6.193 5.936c-.001-.783.002-1.567-.003-2.35a.597.597 0 0 0-.458-.577.59.59 0 0 0-.683.328.907.907 0 0 0-.062.352c-.004 1.492-.003 2.984-.002 4.476 0 .06.002.121.008.181a.592.592 0 0 0 .468.508c.397.076.728-.196.731-.611.004-.768.001-1.537.001-2.307zm-3.733.687c0 .274-.005.521.002.768.003.093-.031.144-.106.19a2.168 2.168 0 0 1-.905.292c-.819.097-1.572-.333-1.872-1.081a2.213 2.213 0 0 1-.125-1.14 1.76 1.76 0 0 1 1.984-1.513c.359.05.674.194.968.396a.616.616 0 0 0 .513.112.569.569 0 0 0 .448-.464c.055-.273-.055-.484-.278-.637-.791-.545-1.677-.659-2.583-.464-2.006.432-2.816 2.512-2.08 4.196.481 1.101 1.379 1.613 2.546 1.693.793.054 1.523-.148 2.2-.56.265-.161.438-.385.447-.698.014-.522.014-1.045.001-1.568-.007-.297-.235-.549-.51-.557a37.36 37.36 0 0 0-1.64-.001c-.21.004-.394.181-.446.385a.494.494 0 0 0 .217.559.714.714 0 0 0 .313.088c.296.011.592.004.906.004zm6.477-2.519h.171c.811 0 1.623.002 2.434-.001.383-.001.632-.286.577-.654-.041-.274-.281-.455-.611-.455h-3.074c-.474 0-.711.237-.711.713v4.479c0 .243.096.436.306.56.41.241.887-.046.896-.545.009-.504.002-1.008.002-1.511v-.177h.169c.7 0 1.4.001 2.1-.001a.543.543 0 0 0 .535-.388c.071-.235-.001-.488-.213-.611a.87.87 0 0 0-.407-.105c-.667-.01-1.335-.005-2.003-.005h-.172V8.004z"></path></svg></span>';
                }else if($chat['lastMessage']['message_type'] == 'contact'){
                    $msgBody = trans('main.contact');
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-user-alt"></i></span>';
                }else if($chat['lastMessage']['message_type'] == 'list'){
                    $msgBody = (isset($chat['lastMessage']['metadata']['title']) ? $chat['lastMessage']['metadata']['title'] . ' ' : '' ).$chat['lastMessage']['metadata']['body'] . ' ' . $chat['lastMessage']['metadata']['footer'];
                    $msgBody = (mb_strlen($msgBody) > 85 ? mb_mb_substr($msgBody, 0, 85) . ' ........' : $msgBody);
                }else if($chat['lastMessage']['message_type'] == 'poll'){
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-poll"></i></span>';
                }else if($chat['lastMessage']['message_type'] == 'product'){
                    $msgIcon = '<span class="fa-icon"><svg width="24" height="24" viewBox="0 0 24 24" class=""><g fill="none" fill-rule="evenodd"><path d="M3.555 5.111h16.888V3H3.555v2.111Zm0 1.057L2.5 11.447v2.111h1.055v6.332H14.11v-6.332h4.224v6.332h2.111v-6.332H21.5v-2.111l-1.055-5.28H3.555ZM5.666 17.78h6.332v-4.223H5.666v4.223Z" id="Page-1-Copy" fill="currentColor"></path></g></svg></span>';
                }else if($chat['lastMessage']['message_type'] == 'order'){
                    $msgIcon = '<span class="fa-icon"><i class="icon-md la la-shopping-cart"></i></span>';
                    $msgBody = @$chat['lastMessage']['metadata']['itemCount'].' '.trans('main.items');
                }else if($chat['lastMessage']['message_type'] == 'call'){
                    if($chat['lastMessage']['metadata']['isVideo'] == true){
                        $msgIcon = '<span class="svg-icon text-danger"><svg viewBox="0 0 19 16" height="16" width="19" preserveAspectRatio="xMidYMid meet" class="" version="1.1" x="0px" y="0px" enable-background="new 0 0 19 16" xml:space="preserve"><path fill="currentColor" d="M11.2,1.8H3.3C2,1.8,1,2.8,1,4.1V11c0,1.1,1,2.2,2.3,2.2h8c1.2,0,2.3-1,2.3-2.3V4.1 C13.5,2.8,12.4,1.8,11.2,1.8z M10.3,5.3L6.4,9.2h2.1c0.2,0,0.4,0.2,0.4,0.4V10c0,0.2-0.2,0.4-0.4,0.4H4.7c-0.2,0-0.4-0.2-0.4-0.4 V6.2c0-0.2,0.2-0.4,0.4-0.4h0.4c0.2,0,0.4,0.2,0.4,0.4v2.1l3.9-3.9c0.2-0.2,0.4-0.1,0.5,0l0.3,0.3C10.4,4.9,10.4,5.1,10.3,5.3z  M15.1,5.3c-0.2,0.1-0.3,0.3-0.3,0.5v3.3c0,0.2,0.1,0.4,0.3,0.5l2.9,2.1V3.2L15.1,5.3z"></path></svg></span>';
                        $msgBody = trans('main.missedVideo');
                    }else{
                        $msgIcon = '<span class="svg-icon text-danger"><svg viewBox="0 0 20 17" height="17" width="20" preserveAspectRatio="xMidYMid meet" class="" version="1.1" x="0px" y="0px" enable-background="new 0 0 20 17" xml:space="preserve"><path fill="currentColor" d="M18.2,12.1L18.2,12.1c-1.5-1.8-5-2.7-8.2-2.7s-6.7,1-8.2,2.7c-0.7,0.8-0.3,2.3,0.2,2.8 c0.2,0.2,0.3,0.3,0.5,0.3c1.4,0,3.6-0.7,3.6-0.7c0.5-0.2,0.8-0.5,0.8-1c0,0,0-0.7,0-1.3c0.7-1.2,5.4-1.2,6.4-0.1c0,0,0,0,0.1,0.1 v1.3c0,0.2,0.1,0.4,0.2,0.6c0.1,0.2,0.3,0.3,0.5,0.4c0,0,2.2,0.7,3.6,0.7C17.9,15.2,19.1,13.2,18.2,12.1z M5.4,3.2l4.7,4.6l5.8-5.7 L15,1.3L10.1,6L6.4,2.3h2.5V1H4.1v4.8h1.3C5.4,5.8,5.4,3.2,5.4,3.2z"></path></svg></span>';
                        $msgBody = trans('main.missedVoice');
                    }
                }
            }
        }
    @endphp
    @if(isset($chat['lastMessage']))
    <div class="card py-3 px-3 mb-1 chatItem {{$chat['pinned'] > 0 ? 'pinned' : ''}}" data-pin="{{$chat['pinned']}}" data-id="{{$chat['id']}}">
        <div class="d-flex">
            <div class="symbol symbol-circle symbol-50 mr-3">
                <img alt="Pic" src="{{$chat['image']}}" />
            </div>
            <div class="d-flex flex-column mx-2" style="width: 60%;" wire:click="openMessages('{{$chat['id']}}')">
                <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg" dir="ltr">{{$chat['name']}}</a>
                <span class="chatPresence text-muted font-weight-bold font-size-sm"></span>
                <span class="chatMsg text-muted font-weight-bold font-size-sm">{!! $msgIcon.' '.$senderText.' '.$msgBody !!}</span>
            </div>
        </div>
        <div class="chatDetails mx-5">
            <span class="text-muted font-weight-bold font-size-sm d-block">{{$chat['last_time']}}</span>
            <div class="row text-right d-block" style="margin: 0;">
                <span class="svg-icon text-muted svg-icon-md mt-1 d-inline-block {{$chat['archived'] > 0 ? '' : 'hidden'}}" data-toggle="tooltip" data-original-title="Archived">
                    <i class="icon-md text-muted la la-archive"></i>
                </span>
                <span class="label label-md label-success mt-1 mx-1 font-weight-bold d-inline-block font-size-h6 {{$chat['unreadCount'] > 0 ? '' : 'hidden'}}" style="padding: 1px 6px">{{$chat['unreadCount']}}</span>
                <span class="svg-icon text-muted svg-icon-md mt-2 pinIcon d-inline-block {{$chat['pinned'] > 0 ? '' : 'hidden'}}" data-toggle="tooltip" data-original-title="Pinned">
                    <svg height="15" width="15" preserveAspectRatio="xMidYMid meet" class="">
                        <path fill="currentColor" d="M12.074 4.21 8.7 8.232l.116 4.233a.4.4 0 0 1-.657.318L.43 6.297a.4.4 0 0 1 .199-.702l4.196-.622L8.196.957a.63.63 0 0 1 .887-.078l2.914 2.445a.63.63 0 0 1 .077.887ZM1.294 14.229a.713.713 0 0 1-1.09-.915l2.674-3.64 1.536 1.288-3.12 3.267Z"></path>
                    </svg>
                </span>
                <span class="fa-icon text-muted mx-1 mt-1 d-inline-block {{$chat['muted'] > 0 ? '' : 'hidden'}}" data-toggle="tooltip" data-original-title="{{$chat['muted_until']}}">
                    <i class="la la-volume-mute text-muted icon-lg"></i>
                </span>
                @foreach($chat['labelsArr'] as $labelObj)
                <span class="catLabel mt-1 d-inline-block fa-icon" data-toggle="tooltip" data-original-title="{{$labelObj['name_ar']}}"> <i class="icon-md fas fa-tag label-cat{{$labelObj['color_id']}}"></i></span>
                @endforeach
                @if(str_contains($chat['id'], '@g.us'))
                <span class="mt-1 d-inline-block fa-icon" data-toggle="tooltip" data-original-title="{{trans('main.groupChat')}}"> <i class="icon-lg la la-users"></i></span>
                @endif
                @foreach($chat['moderatos'] as $moderator)
                <span class="catLabel mt-1 d-inline-block fa-icon" data-toggle="tooltip" data-original-title="{{ucwords($moderator['name'])}}"> <i class="icon-md fas fa-user"></i></span>
                @endforeach
            </div>
        </div>
        @if(\Session::get('is_admin') == 1)
        <div class="card-toolbar">
            <div class="dropdown dropdown-inline">
                <a href="#" class="btn btn-hover-light-primary btn-xs btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="la la-angle-down"></i>
                </a>
                <div class="dropdown-menu p-0 m-0 dropdown-menu-sm dropdown-menu-left" dir="ltr">
                    <ul class="navi navi-hover">
                        <li class="navi-item">
                            <a href="#" class="navi-link p-2" onclick="Livewire.emitTo('chats','readChat','{{$chat['id']}}')">
                                <span class="text-dark">
                                    @if($chat['unreadCount'] <= 0)
                                    Read <i class="la la-check-double icon-md"></i>
                                    @else
                                    Un-Read <i class="la la-check icon-md"></i>
                                    @endif
                                </span>
                            </a>
                        </li>

                        <li class="navi-item">
                            <a href="#" class="navi-link p-2" onclick="Livewire.emitTo('chats','pinChat','{{$chat['id']}}')">
                                <span class="text-dark">
                                    @if($chat['pinned'] == 0) Pin  @else Un-Pin  @endif
                                    <span class="svg-icon text-muted svg-icon-md mt-2 pinIcon d-inline-block" style="margin-right: .1px;margin-left: .1px;">
                                        <svg height="15" width="15" preserveAspectRatio="xMidYMid meet" style="width: 15px !important;height:15px !important;">
                                            <path fill="currentColor" d="M12.074 4.21 8.7 8.232l.116 4.233a.4.4 0 0 1-.657.318L.43 6.297a.4.4 0 0 1 .199-.702l4.196-.622L8.196.957a.63.63 0 0 1 .887-.078l2.914 2.445a.63.63 0 0 1 .077.887ZM1.294 14.229a.713.713 0 0 1-1.09-.915l2.674-3.64 1.536 1.288-3.12 3.267Z"></path>
                                        </svg>
                                    </span>
                                </span>
                            </a>
                        </li>

                        <li class="navi-item">
                            <a href="#" class="navi-link p-2" onclick="Livewire.emitTo('chats','archiveChat','{{$chat['id']}}')">
                                <span class="text-dark">
                                    @if($chat['archived'] == 0) Archive   @else Un-Archive   @endif
                                    <i class="la la-archive icon-md"></i>
                                </span>
                            </a>
                        </li>

                        <li class="navi-item muteItem">
                            <a href="#" class="navi-link p-2" onclick="Livewire.emit('showMuteModal','{{$chat['id']}}','{{$chat['muted']}}')">
                                @if($chat['muted'] == 0)
                                <span class="text-dark">
                                    Mute <i class="la la-volume-mute icon-md"></i>
                                </span>
                                @else
                                <span class="text-dark">
                                    Un-Mute <i class="la la-volume-up icon-md"></i>
                                </span>
                                @endif
                            </a>
                        </li>
                        @if(\Session::get('BUSINESS') == 1)
                        <li class="navi-item labelChat" data-id="{{$chat['id']}}" data-labels="{{isset($chat['labels']) && $chat['labels'] != ',' ? $chat['labels'] : ''}}">
                            <a href="#" class="navi-link p-2" onclick="">
                                <span class="text-dark">
                                    Label <i class="la la-tag icon-md"></i>
                                </span>
                            </a>
                        </li>
                        @endif
                        {{-- <li class="navi-item">
                            <a href="#" class="navi-link p-2" onclick="">
                                <span class="text-dark">
                                    Clear <i class="la la-times-circle icon-md"></i>
                                </span>
                            </a>
                        </li> --}}
                        @if(str_contains($chat['id'], '@g.us'))
                        <li class="navi-item" onclick="Livewire.emitTo('chats','leaveGroup','{{$chat['id']}}')">
                            <a href="#" class="navi-link p-2" onclick="">
                                <span class="text-dark">
                                    <i class="flaticon-logout icon-md"></i> Leave Group 
                                </span>
                            </a>
                        </li>
                        @endif

                        @if(str_contains($chat['id'], '@c.us'))
                        <li class="navi-item" onclick="Livewire.emitTo('chats','blockChat','{{$chat['id']}}')">
                            <a href="#" class="navi-link p-2" onclick="">
                                <span class="text-dark">
                                    {{$chat['blocked'] == 0 ? 'Block' : 'UnBlock'}} <i class="la la-ban icon-md"></i>
                                </span>
                            </a>
                        </li>
                        @endif
                        <li class="navi-item" onclick="Livewire.emitTo('chats','deleteChat','{{$chat['id']}}')">
                            <a href="#" class="navi-link p-2" onclick="">
                                <span class="text-dark">
                                    Delete <i class="la la-trash icon-md"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
</div>