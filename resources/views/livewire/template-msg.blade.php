<div>
    <div class="templates" style="min-width: 200px;">
        @if($msg['metadata']['hasPreview'] == 0)
        @php
            $arr = explode("\r\n \r\n", $msg['body']);
            $title = $arr[0];
            $body = isset($arr[1]) ? $arr[1] : $arr[0];
        @endphp

        @if($title != $body)
        <span class="text-muted d-block mt-3 mb-3">{{$title}}</span>
        @endif

        <span class="text-muted d-block mt-3 mb-3">{{$body}}</span>
        @else
        <a href="{{$msg['metadata']['image']}}" target="_blank">
            <img class="mb-3 w-100" src="{{$msg['metadata']['image']}}" alt="Buttons">
        </a>
        <span class="text-muted d-block mt-3 mb-3">{{$msg['metadata']['content']}}</span>
        @endif
        <span class="text-muted d-block mt-3 mb-3">{{$msg['metadata']['footer']}}</span>
        
        <div class="bts text-center">
            @foreach($msg['metadata']['buttons'] as $button)
            @php $button = (array)$button; @endphp
            <button class="btn btn-block btn-secondary">
                @if(isset($button['urlButton']))
                @php $button['urlButton'] = (array)$button['urlButton']; @endphp                
                <a class="w-100" href="{{$button['urlButton']['url']}}" target="_blank" data-toggle="tooltip" data-original-title="{{$button['urlButton']['url']}}">
                    <span class="fa-icon"><i class="la la-external-link text-primary"></i></span>
                    {{$button['urlButton']['title']}}
                </a>
                @endif
                @if(isset($button['callButton']))
                @php $button['callButton'] = (array)$button['callButton']; @endphp                
                <a class="w-100" href="#" data-toggle="tooltip" data-original-title="{{$button['callButton']['phone']}}">
                    <span class="fa-icon"><i class="la la-phone text-primary"></i></span>
                    {{$button['callButton']['title']}}
                </a>
                @endif
                @if(isset($button['normalButton']))
                @php $button['normalButton'] = (array)$button['normalButton']; @endphp                
                {{$button['normalButton']['title']}}
                @endif
            </button>
            @endforeach
        </div>

        <div class="msgDetails text-left mt-2">
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
