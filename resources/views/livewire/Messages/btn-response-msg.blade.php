<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    @if($msg['metadata'] && isset($msg['metadata']['quotedMessage']['messageType']) && $msg['metadata']['quotedMessage']['messageType'] == 'buttonsMessage')
    <div class="replyHeader py-3 px-3 d-flex align-items-center mb-5 replyMsg" data-id="{{array_reverse(explode('_',$msg['metadata']['quotedMessageId']))[0]}}" style="min-width: 200px;">
        <div class="d-flex flex-column align-items-start">
            @if(isset($msg['metadata']['quotedMessage']['fromMe']))
            <span class="text-dark font-weight-bold mb-1">{{$msg['metadata']['quotedMessage']['fromMe'] == 1 ? trans('main.you') : $chatName}}</span>
            @endif
            <span class="text-dark-50 font-weight-bold mb-1">{!!str_replace("\r\n \r\n",'<br>',$msg['metadata']['quotedMessage']['body'])!!}.....</span>
        </div>
    </div>
    @endif
    <div class="text-dark">{{$msg['body']}}</div>
</div>
