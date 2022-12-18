<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="file">
        <a href="{{$msg['body']}}" target="_blank">
            <span class="fa-icon"><i class="icon-xl la la-download"></i></span>
            <span class="fa-icon text-dark">{{$msg['file_name']}}</span>
        </a>
    </div>
</div>
