<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="video">
        <a href="{{$msg['body']}}" target="_blank">
            <video class="{{$msg['caption'] != '' ? 'mb-3' : '' }} d-block" width="320" height="300" controls>
                <source src="{{$msg['body']}}" type="video/mp4">
            </video>
        </a>
        @if($msg['caption'] != '')
        <span class="text-dark">{{$msg['caption']}}</span>
        @endif
    </div>
</div>
