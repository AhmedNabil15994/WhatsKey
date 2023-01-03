<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="text-dark">{{$msg['body']}}</div>
</div>
