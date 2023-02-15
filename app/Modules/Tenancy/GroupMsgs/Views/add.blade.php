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
            'title' => trans('main.groupMsgs'),
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
<div class="alert alert-custom alert-danger" role="alert" >
    <div class="alert-icon"><i class="flaticon-alert"></i></div>
    <div class="alert-text">{{trans('main.groupMsgNotify1')}}</div>
</div>

<div class="alert alert-custom alert-danger" role="alert" >
    <div class="alert-icon"><i class="flaticon-alert"></i></div>
    <div class="alert-text">{{trans('main.groupMsgNotify2')}}</div>
</div>

@if($data->checkAvailBotPlus == 1)
<div class="alert alert-custom alert-dark" role="alert" >
    <div class="alert-icon"><i class="flaticon-warning"></i></div>
    <div class="alert-text">{{trans('main.groupMsgNotify')}}</div>
</div>

<select name="bots" class="hidden">
    @foreach($data->bots as $bot)
    <option value="{{ $bot->id }}" data-type="1">{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.classicBot') }}</option>
    @endforeach
    @foreach($data->botPlus as $plusBot)
    <option value="{{ $plusBot->id }}" data-type="2">{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.smartBot') }}</option>
    @endforeach
</select>
@endif

<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <p class="w-100 bg-success py-5 px-10 text-white text-left">{{ trans('main.groupMsgsVars') }}</p>
    
    <!--begin::Form-->
    <form class="groupMsgForm" method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/create') }}">
        @csrf
        <div class="card-body">
            <div class="form-group mb-4">
                <label>{{ trans('main.group') }}</label>
                <select class="form-control" data-toggle="select2" name="group_id">
                    <option value="">{{ trans('main.choose') }}</option>
                    @foreach($data->groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="inputEmail3">{{ trans('main.interval') }} ( {{trans('main.second')}} )</label>
                <input type="number" class="interval form-control" value="5" name="interval" min="5" placeholder="{{ trans('main.interval') }}">
            </div>

            <div class="form-group">
                <label>{{ trans('main.sending_date') }} :</label>
                <label class="radio radio-outline radio-primary mb-3">
                    <input type="radio" class="first" checked name="sending"/>
                    <span class="mx-2"></span>
                    {{ trans('main.now') }}
                </label>
                <div class="clearfix"></div>
                <label class="radio radio-outline radio-primary mb-3">
                    <input type="radio" class="second" name="sending"/>
                    <span class="mx-2"></span>
                    {{ trans('main.send_at') }}
                </label>
                <div class="clearfix"></div>
                <input type="text" class="hidden form-control form-control-solid datetimepicker-input" id="kt_datetimepicker_5" placeholder="YYYY-MM-DD H:i" name="date" data-toggle="datetimepicker" data-target="#kt_datetimepicker_5"/>
            </div> 

            <div class="form-group">
                <label>{{ trans('main.message_type') }} :</label>
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="message_type">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="1" {{ old('message_type') == 1 ? 'selected' : '' }}>{{ trans('main.text') }}</option>
                    <option value="2" {{ old('message_type') == 2 ? 'selected' : '' }}>{{ trans('main.botPhoto') }}</option>
                    <option value="3" {{ old('message_type') == 3 ? 'selected' : '' }}>{{ trans('main.video') }}</option>
                    <option value="4" {{ old('message_type') == 4 ? 'selected' : '' }}>{{ trans('main.sound') }}</option>
                    <option value="5" {{ old('message_type') == 5 ? 'selected' : '' }}>{{ trans('main.file') }}</option>
                    <option value="8" {{ old('message_type') == 8 ? 'selected' : '' }}>{{ trans('main.mapLocation') }}</option>
                    <option value="9" {{ old('message_type') == 9 ? 'selected' : '' }}>{{ trans('main.whatsappNos') }}</option>
                    <option value="10" {{ old('message_type') == 10 ? 'selected' : '' }}>{{ trans('main.disappearing') }}</option>
                    <option value="11" {{ old('message_type') == 11 ? 'selected' : '' }}>{{ trans('main.mention') }}</option>
                    <option value="16" {{ old('message_type') == 16 ? 'selected' : '' }}>{{ trans('main.link') }}</option>
                    @if($data->checkAvailBotPlus == 1)
                    <option value="30" {{ old('message_type') == 30 ? 'selected' : '' }}>{{ trans('main.botPlus') }}</option>
                    <option value="33" {{ old('message_type') == 33 ? 'selected' : '' }}>{{ trans('main.templateMsg') }}</option>
                    @endif
                    <option value="31" {{ old('message_type') == 31 ? 'selected' : '' }}>{{ trans('main.listMsg') }}</option>
                    <option value="32" {{ old('message_type') == 32 ? 'selected' : '' }}>{{ trans('main.polls') }}</option>
                </select>
            </div>

            {{-- Text Messages Design --}}
            <div class="reply hidden" data-id="1">
                <div class="form-group textWrap">
                    <label> {{ trans('main.messageContent') }}</label>                   
                    <textarea class="form-control" name="replyText" placeholder="{{ trans('main.messageContent') }}">{{ old('reply') }}</textarea>
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div> 
            </div>
            {{-- Photo Messages Design --}}
            <div class="reply hidden" data-id="2">
                <div class="form-group textWrap">
                    <label> {{ trans('main.caption') }}</label>            
                    <input type="text" class="form-control" name="reply" placeholder="{{ trans('main.caption') }}" value="{{ old('reply') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group">
                    <label class="titleLabel" for="attachFile"> {{ trans('main.attachFile') }}</label>   
                    <div class="dropzone dropzone-default kt_dropzone_bot_1">
                        <div class="dropzone-msg dz-message needsclick">
                            <h3 class="dropzone-msg-title">{{ trans('main.dropzoneP') }}</h3>
                        </div>
                    </div>   
                </div>
            </div>
            {{-- Video Messages Design --}}
            <div class="reply hidden" data-id="3">
                <div class="form-group textWrap">
                    <label> {{ trans('main.caption') }}</label>            
                    <input type="text" class="form-control" name="caption" placeholder="{{ trans('main.caption') }}" value="{{ old('reply') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group">
                    <label class="titleLabel" for="attachFile"> {{ trans('main.attachFile') }}</label>   
                    <div class="dropzone dropzone-default kt_dropzone_bot_1">
                        <div class="dropzone-msg dz-message needsclick">
                            <h3 class="dropzone-msg-title">{{ trans('main.dropzoneP') }}</h3>
                        </div>
                    </div>   
                </div>
            </div>
            {{-- Sound Messages Design --}}
            <div class="reply hidden" data-id="4">
                <div class="form-group">
                    <label class="titleLabel" for="attachFile"> {{ trans('main.attachFile') }}</label>   
                    <div class="dropzone dropzone-default kt_dropzone_bot_1">
                        <div class="dropzone-msg dz-message needsclick">
                            <h3 class="dropzone-msg-title">{{ trans('main.dropzoneP') }}</h3>
                        </div>
                    </div>   
                </div>
            </div>
            {{-- Document Messages Design --}}
            <div class="reply hidden" data-id="5">
                <div class="form-group">
                    <label class="titleLabel" for="attachFile"> {{ trans('main.attachFile') }}</label>   
                    <div class="dropzone dropzone-default kt_dropzone_bot_1">
                        <div class="dropzone-msg dz-message needsclick">
                            <h3 class="dropzone-msg-title">{{ trans('main.dropzoneP') }}</h3>
                        </div>
                    </div>   
                </div>
            </div>
            {{-- Location Messages Design --}}
            <div class="reply hidden" data-id="8">
                <div class="form-group">
                    <label> {{ trans('main.lat') }}</label>            
                    <input class="form-control" type="text" value="{{ old('lat') }}" name="lat" placeholder="{{ trans('main.lat') }}">
                </div>
                <div class="form-group">
                    <label> {{ trans('main.lng') }}</label>            
                    <input class="form-control" type="text" value="{{ old('lng') }}" name="lng" placeholder="{{ trans('main.lng') }}">
                </div>
                <div class="form-group textWrap">
                    <label> {{ trans('main.location') }}</label>            
                    <input class="form-control" type="text" value="{{ old('address') }}" name="address" placeholder="{{ trans('main.location') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
            </div>
            {{-- Contact Messages Design --}}
            <div class="reply hidden" data-id="9">
                <div class="form-group">
                    <label> {{ trans('main.whatsappNo') }}</label>            
                    <input type="hidden" name="phone1">
                    <input class="form-control" type="tel" class="form-control" dir="ltr" id="telephone1" value="{{ old('whatsapp_no') }}" name="whatsapp_no" placeholder="{{ trans('main.whatsappNo') }}">
                </div>
            </div>
            {{-- Disappearing Messages Design --}}
            <div class="reply hidden" data-id="10">
                <div class="form-group textWrap">
                    <label> {{ trans('main.messageContent') }}</label>            
                    <textarea class="form-control" name="disappearingText" placeholder="{{ trans('main.messageContent') }}">{{ old('disappearingText') }}</textarea>
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group">
                    <label> {{ trans('main.expires_in') }} ( {{trans('main.minute')}} )</label>            
                    <input class="form-control" type="tel" value="{{ old('expires_in') }}" name="expires_in" placeholder="{{ trans('main.expires_in') }}">
                </div>                
            </div>
            {{-- Mention Messages Design --}}
            <div class="reply hidden" data-id="11">
                <div class="form-group">
                    <label> {{ trans('main.mention') }}</label>            
                    <input type="hidden" name="phone2">
                    <input class="form-control" type="tel" class="form-control" dir="ltr" id="telephone2" value="{{ old('mention') }}" name="mention" placeholder="{{ trans('main.mention') }}">
                </div>
            </div>
            {{-- Link Messages Design --}}
            <div class="reply hidden" data-id="16">
                <div class="form-group">
                    <label> {{ trans('main.url') }}</label>            
                    <input class="form-control" type="text" value="{{ old('https_url') }}" name="https_url" placeholder="{{ trans('main.url') }}">
                </div>
                <div class="form-group">
                    <label> {{ trans('main.urlTitle') }}</label>            
                    <input class="form-control" type="text" value="{{ old('url_title') }}" name="url_title" placeholder="{{ trans('main.urlTitle') }}">
                </div>
                <div class="form-group textWrap">
                    <label> {{ trans('main.urlDesc') }}</label>            
                    <input class="form-control" type="text" value="{{ old('url_desc') }}" name="url_desc" placeholder="{{ trans('main.urlDesc') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
            </div>

            @if($data->checkAvailBotPlus == 1)
            <div class="reply hidden" data-id="30">
                <div class="form-group textRow textWrap">
                    <label>{{ trans('main.title') }} :</label>
                    <input class="form-control" type="text" value="{{ old('title') }}" name="BPtitle" placeholder="{{ trans('main.title') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group textWrap">
                    <label>{{ trans('main.body') }} :</label>
                    <textarea class="form-control" name="BPbody" placeholder="{{ trans('main.body') }}">{{ old('body') }}</textarea>
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group textWrap">
                    <label>{{ trans('main.footer') }} :</label>
                    <input class="form-control" type="text" value="{{ old('footer') }}" name="BPfooter" placeholder="{{ trans('main.footer') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group">
                    <label>{{ trans('main.buttons') }} :</label>
                    <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="buttons">
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
                    <div class="buts mt-5">
                        <div class='form-group mains buttons'>
                            <label class='titleLabel'>{{ trans('main.btnData',['button'=>1]) }} :</label>
                            <div class='row'>
                                <div class='col-md-4'>
                                    <div class="form-group textWrap">
                                        <input class="form-control" type='text' name='btn_text_1' value="" placeholder='{{ trans('main.text') }}'>
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <select class="form-control reply_types" data-toggle='select2' name='btn_reply_type_1'>
                                        <option value='1' selected>{{ trans('main.newReply') }}</option>
                                        <option value='2'>{{ trans('main.botMsg') }}</option>
                                    </select>
                                </div>
                                <div class='col-md-4 repy'>
                                    <div class="form-group textWrap">
                                        <textarea class="form-control" name='btn_reply_1' placeholder='{{ trans('main.messageContent') }}' ></textarea>
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>
                                    <select data-toggle="" class='form-control dets hidden' name='btn_msg_1'>
                                        <option value='' selected>{{ trans('main.choose') }}</optin>
                                        @foreach($data->bots as $bot)
                                        <option value="{{ $bot->id }}" data-type="1">{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.classicBot') }}</option>
                                        @endforeach
                                        @foreach($data->botPlus as $plusBot)
                                        <option value="{{ $plusBot->id }}" data-type="2">{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.smartBot') }}</option>
                                        @endforeach
                                    </select>
                                    <input type='hidden' name='btn_msg_type_1' value=''>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            @endif
            <div class="reply hidden" data-id="31">
                <div class="form-group textWrap">
                    <label>{{ trans('main.title') }} :</label>
                    <input class="form-control" type="text" value="{{ old('title') }}" name="LMtitle" placeholder="{{ trans('main.title') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group textWrap">
                    <label>{{ trans('main.body') }} :</label>
                    <textarea class="form-control" name="LMbody" placeholder="{{ trans('main.body') }}">{{ old('body') }}</textarea>
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group textWrap">
                    <label>{{ trans('main.footer') }} :</label>
                    <input class="form-control" type="text" value="{{ old('footer') }}" name="LMfooter" placeholder="{{ trans('main.footer') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group textWrap">
                    <label>{{ trans('main.buttonText') }} :</label>
                    <input class="form-control" type="text" value="{{ old('buttonText') }}" name="buttonText" placeholder="{{ trans('main.buttonText') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group">
                    <label>{{ trans('main.sections') }} :</label>
                    <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="sections">
                        <option value="1" {{ old('sections') == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ old('sections') == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ old('sections') == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ old('sections') == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ old('sections') == 5 ? 'selected' : '' }}>5</option>
                        <option value="6" {{ old('sections') == 6 ? 'selected' : '' }}>6</option>
                        <option value="7" {{ old('sections') == 7 ? 'selected' : '' }}>7</option>
                        <option value="8" {{ old('sections') == 8 ? 'selected' : '' }}>8</option>
                        <option value="9" {{ old('sections') == 9 ? 'selected' : '' }}>9</option>
                        <option value="10" {{ old('sections') == 10 ? 'selected' : '' }}>10</option>
                    </select>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="secs mt-5">
                        <div class='form-group mains lists'>
                            <label>{{ trans('main.sectionData',['section'=>1]) }} :</label>
                            <div class='optionsRow'>
                                <div class='row'>
                                    <div class='col-md-6'>
                                        <div class="form-group textWrap">
                                            <label>{{ trans('main.title') }} :</label>
                                            <input class="form-control" type='text' name='title_1' value="" placeholder='{{ trans('main.title') }}'>
                                            <i class="la la-smile icon-xl emoji-icon"></i>
                                            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class="form-group">
                                            <label>{{ trans('main.options') }} :</label>
                                            <select data-toggle='select2' class='options form-control' name='options_1'>
                                                <option value="1" {{ old('options_1') == 1 ? 'selected' : '' }}>1</option>
                                                <option value="2" {{ old('options_1') == 2 ? 'selected' : '' }}>2</option>
                                                <option value="3" {{ old('options_1') == 3 ? 'selected' : '' }}>3</option>
                                                <option value="4" {{ old('options_1') == 4 ? 'selected' : '' }}>4</option>
                                                <option value="5" {{ old('options_1') == 5 ? 'selected' : '' }}>5</option>
                                                <option value="6" {{ old('options_1') == 6 ? 'selected' : '' }}>6</option>
                                                <option value="7" {{ old('options_1') == 7 ? 'selected' : '' }}>7</option>
                                                <option value="8" {{ old('options_1') == 8 ? 'selected' : '' }}>8</option>
                                                <option value="9" {{ old('options_1') == 9 ? 'selected' : '' }}>9</option>
                                                <option value="10" {{ old('options_1') == 10 ? 'selected' : '' }}>10</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="row items item_1_1">
                                <label class='w-100' style="padding: 15px;">{{ trans('main.itemData',['item'=>1]) }} :</label>
                                <div class="row repy w-100">
                                    <input type='hidden' name='btn_msg_type_1_1' value=''>

                                    <div class="col-md-3">
                                        <div class="form-group textWrap">
                                            <input type="text" class="form-control" name="item_title_1_1" placeholder='{{ trans('main.title') }}'>
                                            <i class="la la-smile icon-xl emoji-icon"></i>
                                            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group textWrap">
                                            <textarea class="form-control" name='item_description_1_1' placeholder='{{ trans('main.desc') }}' ></textarea>
                                            <i class="la la-smile icon-xl emoji-icon"></i>
                                            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <select data-toggle='select2' class='reply_types form-control' name='item_reply_type_1_1'>
                                            <option value='1' selected>{{ trans('main.newReply') }}</option>
                                            <option value='2'>{{ trans('main.botMsg') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group textWrap textReply">
                                            <textarea name='btn_reply_1_1' class="replyText form-control" placeholder='{{ trans('main.messageContent') }}' ></textarea>
                                            <i class="la la-smile icon-xl emoji-icon"></i>
                                            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                        </div>

                                        <select data-toggle="" class='form-control dets hidden' name='btn_msg_1_1'>
                                            <option value='' selected>{{ trans('main.choose') }}</optin>
                                            @foreach($data->bots as $bot)
                                            <option value="{{ $bot->id }}" data-type="1">{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.classicBot') }}</option>
                                            @endforeach
                                            @foreach($data->botPlus as $plusBot)
                                            <option value="{{ $plusBot->id }}" data-type="2">{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.smartBot') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="reply hidden" data-id="32">
                <div class="form-group textWrap">
                    <label>{{ trans('main.body') }} :</label>
                    <textarea class="form-control" name="PLbody" placeholder="{{ trans('main.body') }}">{{ old('body') }}</textarea>
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
                                    <div class="form-group textWrap">
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
            </div>
            <div class="reply hidden" data-id="33">
                <div class="form-group textWrap">
                    <label>{{ trans('main.title') }} :</label>
                    <input class="form-control" type="text" value="{{ old('TMtitle') }}" name="TMtitle" placeholder="{{ trans('main.title') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group textWrap">
                    <label>{{ trans('main.body') }} :</label>
                    <textarea class="form-control" name="TMbody" placeholder="{{ trans('main.body') }}">{{ old('TMbody') }}</textarea>
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group textWrap">
                    <label>{{ trans('main.footer') }} :</label>
                    <input class="form-control" type="text" value="{{ old('TMfooter') }}" name="TMfooter" placeholder="{{ trans('main.footer') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group">
                    <label>{{ trans('main.buttons') }} :</label>
                    <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="TMbuttons">
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
                    <div class="templates mt-5">
                        <div class='form-group mains templateMsgs mb-0'>
                            <label class='titleLabel'>{{ trans('main.btnData',['button'=>1]) }} :</label>
                            <div class='row'>
                                <div class='col-md-4'>
                                    <div class="form-group textWrap">
                                        <input class="form-control" type='text' name='btn_text_1' value="" placeholder='{{ trans('main.text') }}'>
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>
                                </div>
                                <div class='col-md-4'>
                                    <select data-toggle='select2' class='button_types form-control' name='btn_type_1'>
                                        <option value='1' selected>{{ trans('main.urlButton') }}</option>
                                        <option value='2'>{{ trans('main.callButton') }}</option>
                                        <option value='3'>{{ trans('main.normalButton') }}</option>
                                    </select>
                                </div>
                                <div class='col-md-4 repy'>
                                    <div class="form-group textWrap input">
                                        <input type="text" class="form-control" name="url_1" placeholder='{{ trans('main.url') }}'>
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>

                                    <select data-toggle='' class='reply_types form-control hidden' name='btn_reply_type_1'>
                                        <option value='1' selected>{{ trans('main.newReply') }}</option>
                                        <option value='2'>{{ trans('main.botMsg') }}</option>
                                    </select>

                                    <div class="form-group textWrap hidden textarea mt-3">
                                        <textarea class="form-control" name='btn_reply_1' placeholder='{{ trans('main.messageContent') }}' ></textarea>
                                        <i class="la la-smile icon-xl emoji-icon"></i>
                                        <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                    </div>

                                    <select data-toggle="" class='dets form-control mt-3 hidden' name='btn_msg_1'>
                                        <option value='' selected>{{ trans('main.choose') }}</optin>
                                        @foreach($data->bots as $bot)
                                        <option value="{{ $bot->id }}" data-type="1">{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.bot') }}</option>
                                        @endforeach
                                        @foreach($data->botPlus as $plusBot)
                                        <option value="{{ $plusBot->id }}" data-type="2">{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.botPlus') }}</option>
                                        @endforeach
                                    </select>
                                    <input type='hidden' name='btn_msg_type_1' value=''>
                                </div>
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

@section('modals')
{{-- @include('tenant.Partials.tipsModal')
@include('tenant.Partials.alertModal') --}}
@endsection

@section('scripts')
<script src="{{ asset('assets/tenant/components/addMsg.js') }}"></script>
<script src="{{ asset('assets/tenant/components/addBotPlus.js') }}"></script>
<script src="{{ asset('assets/tenant/components/addListMsg.js') }}"></script>
<script src="{{ asset('assets/tenant/components/addPoll.js') }}"></script>
<script src="{{ asset('assets/tenant/components/addTemplateMsg.js') }}"></script>
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection