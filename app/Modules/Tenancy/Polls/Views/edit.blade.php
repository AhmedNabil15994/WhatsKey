{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tenant/css/photoswipe.css') }}" />
<style>
    .form-group.textWrap emoji-picker{
        top: 40px;
    }
    html[dir="ltr"] .form-group.textWrap emoji-picker{
        right: 30px;
    }
    html[dir="rtl"] .form-group.textWrap emoji-picker{
        left: 30px;
    }
</style>
@endsection
@section('breadcrumbs')
@include('tenant.Layouts.breadcrumb',[
    'breadcrumbs' => [
        [
            'title' => trans('main.menu'),
            'url' => \URL::to('/dashboard')
        ],
        [
            'title' => trans('main.smartBot'),
            'url' => \URL::to('/'.$data->designElems['mainData']['url'])
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
<select name="bots" class="hidden">
    @foreach($data->bots as $bot)
    <option value="{{ $bot->id }}" data-type="1">{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.classicBot') }}</option>
    @endforeach
    @foreach($data->botPlus as $plusBot)
    <option value="{{ $plusBot->id }}" data-type="2">{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.smartBot') }}</option>
    @endforeach
</select>
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <!--begin::Form-->
    <form method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/update/'.$data->data->id) }}">
        @csrf
        <div class="card-body">
            <input type="hidden" name="status" value="{{ $data->data->status }}">
            <div class="form-group">
                <label>{{ trans('main.messageType') }} :</label>                            
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="message_type">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="1" {{ $data->data->message_type == 1 ? 'selected' : '' }}>{{ trans('main.equal') }}</option>
                    <option value="2" {{ $data->data->message_type == 2 ? 'selected' : '' }}>{{ trans('main.part') }}</option>
                </select>
            </div> 
            <div class="form-group textWrap">
                <label>{{ trans('main.clientMessage') }} :</label>
                <input class="form-control" type="text" value="{{ $data->data->message }}" name="message" placeholder="{{ trans('main.clientMessage') }}">
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group textWrap">
                <label>{{ trans('main.body') }} :</label>
                <textarea class="form-control" name="body" placeholder="{{ trans('main.body') }}">{{ $data->data->body }}</textarea>
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group">
                <label>{{ trans('main.selectableOptionsCount') }} :</label>
                <input class="form-control" type="tel" value="{{ $data->data->selected_options }}" name="selected_options" placeholder="{{ trans('main.selectableOptionsCount') }}">
            </div>
            <div class="form-group">
                <label>{{ trans('main.options') }} :</label>
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="options">
                    <option value="1" {{ $data->data->options == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ $data->data->options == 2 ? 'selected' : '' }}>2</option>
                    <option value="3" {{ $data->data->options == 3 ? 'selected' : '' }}>3</option>
                    <option value="4" {{ $data->data->options == 4 ? 'selected' : '' }}>4</option>
                    <option value="5" {{ $data->data->options == 5 ? 'selected' : '' }}>5</option>
                    <option value="6" {{ $data->data->options == 6 ? 'selected' : '' }}>6</option>
                    <option value="7" {{ $data->data->options == 7 ? 'selected' : '' }}>7</option>
                    <option value="8" {{ $data->data->options == 8 ? 'selected' : '' }}>8</option>
                    <option value="9" {{ $data->data->options == 9 ? 'selected' : '' }}>9</option>
                    <option value="10" {{ $data->data->options == 10 ? 'selected' : '' }}>10</option>
                </select>
                <div class="clearfix"></div>
                <div class="polls mt-5">
                    @foreach($data->data->optionsData as $oneItem)
                    <div class='form-group mains polls'>
                        <label class='titleLabel'>{{ trans('main.btnData',['button'=>$oneItem['id']]) }} :</label>
                        <div class='row'>
                            <div class='col-md-4'>
                                <div class="form-group textWrap">
                                    <input class="form-control" type='text' name='poll_text_{{ $oneItem['id'] }}' value="{{ $oneItem['text'] }}" placeholder='{{ trans('main.text') }}'>
                                    <i class="la la-smile icon-xl emoji-icon"></i>
                                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <select class="form-control reply_types" data-toggle='select2' name='poll_reply_type_{{ $oneItem['id'] }}'>
                                    <option value='1' {{ $oneItem['reply_type'] == 1 ? 'selected' : '' }}>{{ trans('main.newReply') }}</option>
                                    <option value='2' {{ $oneItem['reply_type'] == 2 ? 'selected' : '' }}>{{ trans('main.botMsg') }}</option>
                                </select>
                            </div>
                            <div class='col-md-4 repy'>
                                <div class="form-group textWrap {{ $oneItem['msg_type'] == 0 ? '' : 'hidden'  }}">
                                    <textarea class="form-control" name='poll_reply_{{ $oneItem['id'] }}' placeholder='{{ trans('main.messageContent') }}' >{{ $oneItem['msg_type'] == 0 ? $oneItem['msg'] : ''  }}</textarea>
                                    <i class="la la-smile icon-xl emoji-icon"></i>
                                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                </div>
                                <select data-toggle="{{ $oneItem['msg_type'] > 0 ? 'select2' : ''  }}" class='form-control dets {{ $oneItem['msg_type'] > 0 ? '' : 'hidden'  }}' name='poll_msg_{{ $oneItem['id'] }}'>
                                    <option value='' selected>{{ trans('main.choose') }}</optin>
                                    
                                    @foreach($data->bots as $bot)
                                    <option value="{{ $bot->id }}" data-type="1" {{ $oneItem['msg_type'] == 1 && isset($oneItem['msg']) && $oneItem['msg'] == $bot->id ? 'selected' : '' }}>{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.classicBot') }}</option>
                                    @endforeach

                                    @foreach($data->botPlus as $plusBot)
                                    <option value="{{ $plusBot->id }}" data-type="2" {{ $oneItem['msg_type'] == 2 && isset($oneItem['msg'])  && $oneItem['msg'] == $plusBot->id ? 'selected' : '' }}>{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.smartBot') }}</option>
                                    @endforeach      
                                                                  
                                </select>
                                <input type='hidden' name='poll_msg_type_{{ $oneItem['id'] }}' value='{{ $oneItem['msg_type'] }}'>
                            </div>
                        </div>
                    </div> 
                    @endforeach
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.edit')}}</button>
                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </div>
    </form>
</div>

@endsection


@section('modals')
@include('tenant.Partials.photoswipe_modal')
@endsection

@section('scripts')
<script src="{{ asset('assets/tenant/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/tenant/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/tenant/components/myPhotoSwipe.js') }}"></script>      
<script src="{{ asset('assets/tenant/components/addBotPlus.js') }}"></script>
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection
