<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="link">
        <div class="replyHeader py-3 px-3 mb-3">
            <span class="text-dark d-block">{{$msg['metadata']['title']}}</span>
            <span class="text-muted d-block">{{str_replace('https://','',$msg['metadata']['matchedText'])}}</span>
        </div>
        <div>
            {{$msg['metadata']['description']}} <a href="{{$msg['metadata']['matchedText']}}" target="_blank">{{$msg['metadata']['matchedText']}}</a>
        </div>
    </div>
</div>
