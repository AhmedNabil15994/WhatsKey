{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
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
    <form method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/create') }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>{{ trans('main.messageType') }} :</label>                            
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="message_type">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="1" {{ old('message_type') == 1 ? 'selected' : '' }}>{{ trans('main.equal') }}</option>
                    <option value="2" {{ old('message_type') == 2 ? 'selected' : '' }}>{{ trans('main.part') }}</option>
                </select>
            </div> 
            <div class="form-group textWrap">
                <label>{{ trans('main.clientMessage') }} :</label>
                <input class="form-control" type="text" value="{{ old('message') }}" name="message" placeholder="{{ trans('main.clientMessage') }}">
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group textWrap">
                <label>{{ trans('main.body') }} :</label>
                <textarea class="form-control" name="body" placeholder="{{ trans('main.body') }}">{{ old('body') }}</textarea>
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group">
                <label>{{ trans('main.selectableOptionsCount') }} :</label>
                <input class="form-control" type="tel" value="{{ old('selected_options') }}" name="selected_options" placeholder="{{ trans('main.selectableOptionsCount') }}">
            </div>
            <div class="form-group">
                <label>{{ trans('main.options') }} :</label>
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="options">
                    <option value="1" {{ old('buttons') == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ old('buttons') == 2 ? 'selected' : '' }}>2</option>
                    <option value="3" {{ old('buttons') == 3 ? 'selected' : '' }}>3</option>
                    <option value="4" {{ old('buttons') == 4 ? 'selected' : '' }}>4</option>
                    <option value="5" {{ old('buttons') == 5 ? 'selected' : '' }}>5</option>
                    <option value="6" {{ old('buttons') == 6 ? 'selected' : '' }}>6</option>
                    <option value="7" {{ old('buttons') == 7 ? 'selected' : '' }}>7</option>
                    <option value="8" {{ old('buttons') == 8 ? 'selected' : '' }}>8</option>
                    <option value="9" {{ old('buttons') == 9 ? 'selected' : '' }}>9</option>
                    <option value="10" {{ old('buttons') == 10 ? 'selected' : '' }}>10</option>
                </select>
                <div class="clearfix"></div>
                <div class="polls mt-5">
                    <div class='form-group mains polls'>
                        <label class='titleLabel'>{{ trans('main.btnData',['button'=>1]) }} :</label>
                        <div class='row'>
                            <div class='col-md-4'>
                                <div class="form-group textWrap">
                                    <input class="form-control" type='text' name='poll_text_1' value="" placeholder='{{ trans('main.text') }}'>
                                    <i class="la la-smile icon-xl emoji-icon"></i>
                                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <select class="form-control reply_types" data-toggle='select2' name='poll_reply_type_1'>
                                    <option value='1' selected>{{ trans('main.newReply') }}</option>
                                    <option value='2'>{{ trans('main.botMsg') }}</option>
                                </select>
                            </div>
                            <div class='col-md-4 repy'>
                                <div class="textWrap form-group">
                                    <textarea class="form-control" name='poll_reply_1' placeholder='{{ trans('main.messageContent') }}' ></textarea>
                                    <i class="la la-smile icon-xl emoji-icon"></i>
                                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                </div>
                                <select data-toggle="" class='form-control dets hidden' name='poll_msg_1'>
                                    <option value='' selected>{{ trans('main.choose') }}</optin>
                                    @foreach($data->bots as $bot)
                                    <option value="{{ $bot->id }}" data-type="1">{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.classicBot') }}</option>
                                    @endforeach
                                    @foreach($data->botPlus as $plusBot)
                                    <option value="{{ $plusBot->id }}" data-type="2">{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.smartBot') }}</option>
                                    @endforeach
                                </select>
                                <input type='hidden' name='poll_msg_type_1' value=''>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.add')}}</button>
                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/tenant/components/addPoll.js') }}"></script>
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection