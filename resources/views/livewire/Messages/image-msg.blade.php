<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="image">
        <a href="{{$msg['body']}}" target="_blank">
            <img class="{{$msg['caption'] != '' ? 'mb-3' : '' }} " src="{{$msg['body']}}" alt="{{$msg['file_name']}}">
        </a>
        @if($msg['caption'] != '')
        <span class="text-dark">{{$msg['caption']}}</span>
        @endif
    </div>
</div>
