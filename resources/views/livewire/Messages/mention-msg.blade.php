<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="mention">
        <a href="#" dir="ltr">{{$msg['body']}}</a>
    </div>
</div>
