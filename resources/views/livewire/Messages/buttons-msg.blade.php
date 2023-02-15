<div>
    @php 
    $msg = json_decode(json_encode($msg), true); 
    $msg['metadata']['buttons'] = (array) $msg['metadata']['buttons'];
    @endphp
    <div class="buttons" style="min-width: 200px;">
        @if(@$msg['metadata']['hasPreview'] == 0)
        @php
            $arr = explode("\r\n \r\n", $msg['body']);
            $title = $arr[0];
            $body = isset($arr[1]) ? $arr[1] : $arr[0];
        @endphp

        @if($title != $body)
        <span class="text-dark d-block mt-3 mb-3">{{$title}}</span>
        @endif

        <span class="text-muted d-block mt-3 mb-3">{{$body}}</span>
        @else
        <a href="{{$msg['metadata']['image']}}" target="_blank">
            <img class="mb-3 w-100" src="{{$msg['metadata']['image']}}" alt="Buttons">
        </a>
        <span class="text-muted d-block mt-3 mb-3">{{$msg['metadata']['content']}}</span>
        @endif
        <span class="text-dark d-block mt-3 mb-3">{{$msg['metadata']['footer']}}</span>
        
        <div class="bts text-center">
            @foreach($msg['metadata']['buttons'] as $button)
            @php $button = (array)$button; @endphp  
            @if(isset($button) && isset($button['title']))          
            <button class="btn btn-block btn-{{$msg['fromMe'] ? 'secondary':'white'}}">{{$button['title']}}</button>
            @endif
            @endforeach
        </div>
    </div>
</div>
