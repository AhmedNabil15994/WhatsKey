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
            'title' => trans('main.lists'),
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
                <label>{{ trans('main.title') }} :</label>
                <input class="form-control" type="text" value="{{ $data->data->title }}" name="title" placeholder="{{ trans('main.title') }}">
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group textWrap">
                <label>{{ trans('main.body') }} :</label>
                <textarea class="form-control" name="body" placeholder="{{ trans('main.body') }}">{{ $data->data->body }}</textarea>
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group textWrap">
                <label>{{ trans('main.footer') }} :</label>
                <input class="form-control" type="text" value="{{ $data->data->footer }}" name="footer" placeholder="{{ trans('main.footer') }}">
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group textWrap">
                <label>{{ trans('main.buttonText') }} :</label>
                <input class="form-control" type="text" value="{{ $data->data->buttonText }}" name="buttonText" placeholder="{{ trans('main.buttonText') }}">
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group">
                <label>{{ trans('main.sections') }} :</label>
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="sections">
                    <option value="1" {{ $data->data->sections == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ $data->data->sections == 2 ? 'selected' : '' }}>2</option>
                    <option value="3" {{ $data->data->sections == 3 ? 'selected' : '' }}>3</option>
                    <option value="4" {{ $data->data->sections == 4 ? 'selected' : '' }}>4</option>
                    <option value="5" {{ $data->data->sections == 5 ? 'selected' : '' }}>5</option>
                    <option value="6" {{ $data->data->sections == 6 ? 'selected' : '' }}>6</option>
                    <option value="7" {{ $data->data->sections == 7 ? 'selected' : '' }}>7</option>
                    <option value="8" {{ $data->data->sections == 8 ? 'selected' : '' }}>8</option>
                    <option value="9" {{ $data->data->sections == 9 ? 'selected' : '' }}>9</option>
                    <option value="10" {{ $data->data->sections == 10 ? 'selected' : '' }}>10</option>
                </select>
                <div class="clearfix"></div>
                <hr>
                <div class="secs mt-5">
                    @foreach($data->data->sectionsData as $oneItem)
                    <div class='form-group mains lists'>
                        <label>{{ trans('main.sectionData',['section'=>$oneItem['id']]) }} :</label>
                        <div class='optionsRow'>
                            <div class='row'>
                                <div class='col-md-6'>
                                    <div class="form-group textWrap">
                                        <label>{{ trans('main.title') }} :</label>
                                        <input class="form-control" type='text' name='{{'title_'.$oneItem['id']}}' value="{{$oneItem['title']}}" placeholder='{{ trans('main.title') }}'>
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>
                                </div>
                                <div class='col-md-6'>
                                    <div class="form-group">
                                        <label>{{ trans('main.options') }} :</label>
                                        <select data-toggle='select2' class='options form-control' name='{{'options_'.$oneItem['id']}}'>
                                            <option value="1" {{ count($oneItem['rows']) == 1 ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ count($oneItem['rows']) == 2 ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ count($oneItem['rows']) == 3 ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ count($oneItem['rows']) == 4 ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ count($oneItem['rows']) == 5 ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ count($oneItem['rows']) == 6 ? 'selected' : '' }}>6</option>
                                            <option value="7" {{ count($oneItem['rows']) == 7 ? 'selected' : '' }}>7</option>
                                            <option value="8" {{ count($oneItem['rows']) == 8 ? 'selected' : '' }}>8</option>
                                            <option value="9" {{ count($oneItem['rows']) == 9 ? 'selected' : '' }}>9</option>
                                            <option value="10" {{ count($oneItem['rows']) == 10 ? 'selected' : '' }}>10</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach($oneItem['rows'] as $key => $oneRow)
                        <div class="clearfix"></div>
                        <div class="row items {{ 'item_'.$oneItem['id'].'_'.($key+1) }}">
                            <label class='w-100' style="padding: 15px;">{{ trans('main.itemData',['item'=>($key+1)]) }} :</label>
                            <div class="row repy w-100">
                                <input type='hidden' name='{{'btn_msg_type_'.$oneItem['id'].'_'.($key+1)}}' value='{{ $oneRow['msg_type'] }}'>

                                <div class="col-md-3">
                                    <div class="form-group textWrap">
                                        <input type="text" class="form-control" name="{{'item_title_'.$oneItem['id'].'_'.($key+1)}}" placeholder='{{ trans('main.title') }}' value="{{$oneRow['title']}}">
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>
                                </div>
                                <div class="col-md-3">                                    
                                    <div class="form-group textWrap">
                                        <textarea class="form-control" name='{{'item_description_'.$oneItem['id'].'_'.($key+1)}}' placeholder='{{ trans('main.desc') }}' maxlength="140">{{$oneRow['description']}}</textarea>
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select data-toggle='select2' class='reply_types form-control' name='{{'item_reply_type_'.$oneItem['id'].'_'.($key+1)}}'>
                                        <option value='1' {{ $oneRow['reply_type'] == 1 ? 'selected' : '' }}>{{ trans('main.newReply') }}</option>
                                        <option value='2' {{ $oneRow['reply_type'] == 2 ? 'selected' : '' }}>{{ trans('main.botMsg') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group textWrap textReply {{ $oneRow['msg_type'] == null ? '' : 'hidden'}}">
                                        <textarea class="replyText form-control" name='{{ 'btn_reply_'.$oneItem['id'].'_'.($key+1) }}' placeholder='{{ trans('main.messageContent') }}' maxlength="140">{{ $oneRow['msg_type'] == 0 ? $oneRow['msg'] : ''  }}</textarea>
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>

                                    <select data-toggle="{{ $oneRow['msg_type'] != null ? 'select2' : ''  }}" class='form-control dets {{ $oneRow['msg_type'] != null ? '' : 'hidden'  }}' name='{{ 'btn_msg_'.$oneItem['id'].'_'.($key+1) }}'>
                                        <option value='' selected>{{ trans('main.choose') }}</optin>
                                        @foreach($data->bots as $bot)
                                        <option value="{{ $bot->id }}" data-type="1" {{ $oneRow['msg_type'] == 1 && isset($oneRow['msg']) && $oneRow['msg'] == $bot->id ? 'selected' : '' }}>{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.classicBot') }}</option>
                                        @endforeach
                                        @foreach($data->botPlus as $plusBot)
                                        <option value="{{ $plusBot->id }}" data-type="2" {{ $oneRow['msg_type'] == 2 && isset($oneRow['msg'])  && $oneRow['msg'] == $plusBot->id ? 'selected' : '' }}>{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.smartBot') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                    </div> 
                    @endforeach
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
<script src="{{ asset('assets/tenant/components/addListMsg.js') }}"></script>
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection
