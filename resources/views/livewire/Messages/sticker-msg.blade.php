<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="sticker">
        <a href="{{$msg['body']}}" target="_blank">
            <img width="160" height="160" src="{{$msg['body']}}" alt="sticker">
        </a>
    </div>
</div>
