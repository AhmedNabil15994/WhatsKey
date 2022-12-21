<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="audio">
        <a href="{{$msg['body']}}" target="_blank">
            <audio class="d-block" width="320" height="60" controls>
                <source src="{{$msg['body']}}" type="audio/ogg">
            </audio>
        </a>
    </div>
</div>
