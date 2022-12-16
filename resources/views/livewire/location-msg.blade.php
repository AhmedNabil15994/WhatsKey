<div>
    <div class="location">
        <iframe class="w-100" src="https://www.google.com/maps/embed/v1/place?q={{$msg['metadata']['latitude'].','.$msg['metadata']['longitude']}}&key=AIzaSyCai_Ru6iTKHQjlKrihzsRh_-kz5nRNxGw" width='300' height='200' frameborder='0' style='border:0;' allowfullscreen='' aria-hidden='false' tabindex='0'></iframe>
        @if($msg['body'] != '')
        <span>{{$msg['body']}}</span>
        @endif
        <div class="msgDetails text-left {{$msg['body'] == '' ? 'mt-2' : '' }}">
            <span class="fa-icon">
                @if($msg['sending_status'] == 1)
                <i class="icon-md la la-check"></i>
                @elseif($msg['sending_status'] == 2)
                <i class="icon-md la la-check-double"></i>
                @elseif($msg['sending_status'] == 3)
                <i class="icon-md la la-check-double text-primary"></i>
                @else
                <i class="icon-md la la-clock"></i>
                @endif
            </span>
            <span>{{$msg['date_time']}}</span>
        </div>
    </div>
</div>
