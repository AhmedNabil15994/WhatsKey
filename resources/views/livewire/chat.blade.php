<div>
    @php
        $chat = (array) $chat;
        $chat['lastMessage'] = (array) @$chat['lastMessage'];
    @endphp
    @if($chat['lastMessage'])
    <div class="d-flex align-items-center justify-content-between mb-5 chatItem {{$chat['pinned'] > 0 ? 'pinned' : ''}} " wire:click="openMessages('{{$chat['id']}}')">
        <div class="d-flex align-items-center">
            <div class="symbol symbol-circle symbol-50 mr-3">
                <img alt="Pic" src="{{$chat['image']}}" />
            </div>
            <div class="d-flex flex-column mx-2">
                <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-lg" dir="ltr">{{$chat['name']}}</a>

                <span class="chatMsg text-muted font-weight-bold font-size-sm">{{strlen($chat['lastMessage']['body']) > 100 ? substr($chat['lastMessage']['body'], 0, 100) . ' ........' : $chat['lastMessage']['body']}}</span>
            </div>
        </div>
        <div class="d-flex flex-column align-items-end w-40">
            <span class="text-muted font-weight-bold font-size-sm">{{$chat['last_time']}}</span>
            @if($chat['pinned'] > 0)
            <span class="svg-icon text-muted svg-icon-xl">
                <svg height="15" width="15" preserveAspectRatio="xMidYMid meet" class="">
                    <path fill="currentColor" d="M12.074 4.21 8.7 8.232l.116 4.233a.4.4 0 0 1-.657.318L.43 6.297a.4.4 0 0 1 .199-.702l4.196-.622L8.196.957a.63.63 0 0 1 .887-.078l2.914 2.445a.63.63 0 0 1 .077.887ZM1.294 14.229a.713.713 0 0 1-1.09-.915l2.674-3.64 1.536 1.288-3.12 3.267Z"></path>
                </svg>
            </span>
            @endif

            @if($chat['unreadCount'] > 0)
            <span class="label label-md label-success font-weight-bold">{{$chat['unreadCount']}}</span>
            @endif
        </div>
    </div>
    <hr>
    @endif
</div>