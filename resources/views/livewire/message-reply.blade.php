<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    @if($msg['metadata'] && isset($msg['metadata']['type']) && $msg['metadata']['type'] == 'reply' && isset($msg['metadata']['quotedMessage']) && isset($msg['metadata']['quotedMessage']['fromMe']))
    <div class="replyHeader mt-3 py-3 px-3 d-flex align-items-center mb-3 replyMsg" data-id="{{array_reverse(explode('_',$msg['metadata']['quotedMessageId']))[0]}}" style="min-width: 200px;">
        <div class="d-flex flex-column align-items-start">
            <span class="text-dark font-weight-bold mb-1">{{ $msg['metadata']['quotedMessage']['fromMe'] == 'false' ? $chatName : trans('main.you') }}</span>
            @if($msg['metadata']['quotedMessage']['messageType'] == 'video')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-video"></i> {{($msg['metadata']['quotedMessage']['caption'] != '' ? $msg['metadata']['quotedMessage']['caption'] : $msg['metadata']['quotedMessage']['fileName']) . ' ('.$msg['metadata']['quotedMessage']['metadata']['seconds'].' '.trans('main.second').')'}}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'audio')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-headphones"></i> {{$msg['metadata']['quotedMessage']['fileName'] . ' ('.$msg['metadata']['quotedMessage']['metadata']['seconds'].' '.trans('main.second').')'}}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'image')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-camera"></i> {{$msg['metadata']['quotedMessage']['caption'] != '' ? $msg['metadata']['quotedMessage']['caption'] : $msg['metadata']['quotedMessage']['fileName']}}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'document')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-file-text"></i> {{$msg['metadata']['quotedMessage']['fileName']}}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'sticker')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-sticky-note"></i> {{trans('main.sticker')}}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'gif')
            <span class="text-dark-50 font-weight-bold mb-1"><svg viewBox="0 0 20 20" width="20" height="20" class=""><path fill="currentColor" d="M4.878 3.9h10.285c1.334 0 1.818.139 2.306.4s.871.644 1.131 1.131c.261.488.4.972.4 2.306v4.351c0 1.334-.139 1.818-.4 2.306a2.717 2.717 0 0 1-1.131 1.131c-.488.261-.972.4-2.306.4H4.878c-1.334 0-1.818-.139-2.306-.4s-.871-.644-1.131-1.131-.4-.972-.4-2.306V7.737c0-1.334.139-1.818.4-2.306s.643-.87 1.131-1.131.972-.4 2.306-.4zm6.193 5.936c-.001-.783.002-1.567-.003-2.35a.597.597 0 0 0-.458-.577.59.59 0 0 0-.683.328.907.907 0 0 0-.062.352c-.004 1.492-.003 2.984-.002 4.476 0 .06.002.121.008.181a.592.592 0 0 0 .468.508c.397.076.728-.196.731-.611.004-.768.001-1.537.001-2.307zm-3.733.687c0 .274-.005.521.002.768.003.093-.031.144-.106.19a2.168 2.168 0 0 1-.905.292c-.819.097-1.572-.333-1.872-1.081a2.213 2.213 0 0 1-.125-1.14 1.76 1.76 0 0 1 1.984-1.513c.359.05.674.194.968.396a.616.616 0 0 0 .513.112.569.569 0 0 0 .448-.464c.055-.273-.055-.484-.278-.637-.791-.545-1.677-.659-2.583-.464-2.006.432-2.816 2.512-2.08 4.196.481 1.101 1.379 1.613 2.546 1.693.793.054 1.523-.148 2.2-.56.265-.161.438-.385.447-.698.014-.522.014-1.045.001-1.568-.007-.297-.235-.549-.51-.557a37.36 37.36 0 0 0-1.64-.001c-.21.004-.394.181-.446.385a.494.494 0 0 0 .217.559.714.714 0 0 0 .313.088c.296.011.592.004.906.004zm6.477-2.519h.171c.811 0 1.623.002 2.434-.001.383-.001.632-.286.577-.654-.041-.274-.281-.455-.611-.455h-3.074c-.474 0-.711.237-.711.713v4.479c0 .243.096.436.306.56.41.241.887-.046.896-.545.009-.504.002-1.008.002-1.511v-.177h.169c.7 0 1.4.001 2.1-.001a.543.543 0 0 0 .535-.388c.071-.235-.001-.488-.213-.611a.87.87 0 0 0-.407-.105c-.667-.01-1.335-.005-2.003-.005h-.172V8.004z"></path></svg>{{$msg['metadata']['quotedMessage']['caption'] != '' ? $msg['metadata']['quotedMessage']['caption']: trans('main.gif')}} </span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'locationMessage')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-map-marker-alt"></i> {{ $msg['metadata']['quotedMessage']['body'] != null ? $msg['metadata']['quotedMessage']['body'] : trans('main.location') }}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'contactMessage')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-user-alt"></i> {{ trans('main.contact') }}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'pollMessage')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-poll"></i> {{ $msg['metadata']['quotedMessage']['body'] != null ? $msg['metadata']['quotedMessage']['body'] : trans('main.poll') }}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'product')
            <span class="text-dark-50 font-weight-bold mb-1"><svg width="24" height="24" viewBox="0 0 24 24" class=""><g fill="none" fill-rule="evenodd"><path d="M3.555 5.111h16.888V3H3.555v2.111Zm0 1.057L2.5 11.447v2.111h1.055v6.332H14.11v-6.332h4.224v6.332h2.111v-6.332H21.5v-2.111l-1.055-5.28H3.555ZM5.666 17.78h6.332v-4.223H5.666v4.223Z" id="Page-1-Copy" fill="currentColor"></path></g></svg> {{ $msg['metadata']['quotedMessage']['body'] != null ? $msg['metadata']['quotedMessage']['body'] : trans('main.product') }} <br> {{ $msg['metadata']['quotedMessage']['metadata']['price'] . ' ' . $msg['metadata']['quotedMessage']['metadata']['currency'] }}</span>

            @elseif($msg['metadata']['quotedMessage']['messageType'] == 'order')
            <span class="text-dark-50 font-weight-bold mb-1"><i class="icon-xl la la-shopping-cart"></i> {{ $msg['metadata']['quotedMessage']['body'] != null ? $msg['metadata']['quotedMessage']['body'] : trans('main.order').' '. $msg['metadata']['quotedMessage']['metadata']['orderId'] }} <br> {{ $msg['metadata']['quotedMessage']['metadata']['price'] . ' ' . $msg['metadata']['quotedMessage']['metadata']['currency'] }}</span>

            @else
            <span class="text-dark-50 font-weight-bold mb-1">{{$msg['metadata']['quotedMessage']['body']}}</span>
            @endif
        </div>
    </div>

    @elseif($msg['metadata'] && isset($msg['metadata']['type']) && $msg['metadata']['type'] == 'forward')
    <div class="replyHeader py-3 px-1 text-right {{$msg['message_type'] == 'text' ? 'mb-5' : ''}}" style="min-width: 200px;">
        {{trans('main.forwarded')}} <span class="fa-icon w-100"><svg viewBox="0 0 16 16" height="16" width="16" preserveAspectRatio="xMidYMid meet" class="" version="1.1"><path d="M9.51866667,3.87533333 C9.51866667,3.39333333 10.1006667,3.152 10.4406667,3.49266667 L14.4706667,7.52666667 C14.682,7.738 14.682,8.07933333 14.4706667,8.29066667 L10.4406667,12.3246667 C10.1006667,12.6646667 9.51866667,12.424 9.51866667,11.942 L9.51866667,10.1206667 C6.12133333,10.1206667 3.63266667,11.0906667 1.78266667,13.1946667 C1.61866667,13.3806667 1.31466667,13.2226667 1.38133333,12.984 C2.33466667,9.53533333 4.66466667,6.31466667 9.51866667,5.62066667 L9.51866667,3.87533333 Z" fill="currentColor"></path></svg> </span>
    </div>
    @endif
</div>
