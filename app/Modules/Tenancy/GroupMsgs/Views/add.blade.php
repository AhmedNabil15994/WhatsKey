{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')

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
                <input type="tel" class="interval form-control" name="interval" placeholder="{{ trans('main.interval') }}">
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
                    <option value="30" {{ old('message_type') == 30 ? 'selected' : '' }}>{{ trans('main.smartBot') }}</option>
                    @endif
                </select>
            </div>

            {{-- Text Messages Design --}}
            <div class="reply hidden" data-id="1">
                <div class="form-group">
                    <label> {{ trans('main.messageContent') }}</label>                   
                    <textarea class="form-control" name="replyText" placeholder="{{ trans('main.messageContent') }}">{{ old('reply') }}</textarea>
                </div> 
            </div>
            {{-- Photo Messages Design --}}
            <div class="reply hidden" data-id="2">
                <div class="form-group">
                    <label> {{ trans('main.caption') }}</label>            
                    <input type="text" class="form-control" name="reply" placeholder="{{ trans('main.caption') }}" value="{{ old('reply') }}">
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
                <div class="form-group">
                    <label> {{ trans('main.caption') }}</label>            
                    <input type="text" class="form-control" name="caption" placeholder="{{ trans('main.caption') }}" value="{{ old('reply') }}">
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
                <div class="form-group">
                    <label> {{ trans('main.location') }}</label>            
                    <input class="form-control" type="text" value="{{ old('address') }}" name="address" placeholder="{{ trans('main.location') }}">
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
                <div class="form-group">
                    <label> {{ trans('main.messageContent') }}</label>            
                    <textarea class="form-control" name="disappearingText" placeholder="{{ trans('main.messageContent') }}">{{ old('disappearingText') }}</textarea>
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
                <div class="form-group">
                    <label> {{ trans('main.urlDesc') }}</label>            
                    <input class="form-control" type="text" value="{{ old('url_desc') }}" name="url_desc" placeholder="{{ trans('main.urlDesc') }}">
                </div>
            </div>

            @if($data->checkAvailBotPlus == 1)
            <div class="reply hidden" data-id="30">
                <div class="form-group textRow">
                    <label>{{ trans('main.title') }} :</label>
                    <input class="form-control" type="text" value="{{ old('title') }}" name="title" placeholder="{{ trans('main.title') }}">
                </div>
                <div class="form-group">
                    <label>{{ trans('main.body') }} :</label>
                    <textarea class="form-control" name="body" placeholder="{{ trans('main.body') }}">{{ old('body') }}</textarea>
                </div>
                <div class="form-group">
                    <label>{{ trans('main.footer') }} :</label>
                    <input class="form-control" type="text" value="{{ old('footer') }}" name="footer" placeholder="{{ trans('main.footer') }}">
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
                        <div class='form-group mains'>
                            <label class='titleLabel'>{{ trans('main.btnData',['button'=>1]) }} :</label>
                            <div class='row'>
                                <div class='col-md-4'>
                                    <input class="form-control" type='text' name='btn_text_1' value="" placeholder='{{ trans('main.text') }}'>
                                </div>
                                <div class='col-md-4'>
                                    <select class="form-control reply_types" data-toggle='select2' name='btn_reply_type_1'>
                                        <option value='1' selected>{{ trans('main.newReply') }}</option>
                                        <option value='2'>{{ trans('main.botMsg') }}</option>
                                    </select>
                                </div>
                                <div class='col-md-4 repy'>
                                    <textarea class="form-control" name='btn_reply_1' placeholder='{{ trans('main.messageContent') }}' maxlength="140"></textarea>
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
@endsection