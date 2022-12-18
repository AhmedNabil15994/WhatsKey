<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="location">
        <iframe class="w-100" src="https://www.google.com/maps/embed/v1/place?q={{$msg['metadata']['latitude'].','.$msg['metadata']['longitude']}}&key=AIzaSyCai_Ru6iTKHQjlKrihzsRh_-kz5nRNxGw" width='300' height='200' frameborder='0' style='border:0;' allowfullscreen='' aria-hidden='false' tabindex='0'></iframe>
        @if($msg['body'] != '')
        <span class="text-dark">{{$msg['body']}}</span>
        @endif
    </div>
</div>
