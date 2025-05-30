<div>
    @php $msg = json_decode(json_encode($msg), true); @endphp
    <div class="poll" style="min-width: 250px;">
        <span class="text-dark font-weight-boldest d-block mt-3 mb-3">{{$msg['body']}}</span>
        @foreach($msg['metadata']['options'] as $one)
        <div class="w-100 mb-5 px-5">
            <div class="float-left">
                <label class="radio radio-outline radio-success">
                    <input type="radio" value="{{$one}}"  name="radios"/>
                    <span class="mx-2"></span>
                    {{$one}}
                </label>
            </div>
            <div class="float-right text-left">
                <p class="score mb-0">0</p>
            </div>
            <div class="clearfix"></div>
            <div class="progress mt-2">
                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        @endforeach
        <hr>
        <div class="row mb-3 text-center">
            <a href="#" class="w-100">{{trans('main.viewVotes')}}</a>
        </div>
        <hr>
    </div>
</div>
