<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="text-dark">{{$msg['body']}}</div>
    @if(isset($msg['metadata']['selectedOptionDescription']) && !empty($msg['metadata']['selectedOptionDescription']))
    <div class="text-muted">{{$msg['metadata']['selectedOptionDescription']}}</div>
    @endif
</div>
