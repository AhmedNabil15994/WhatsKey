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
            'title' => trans('main.classicBot'),
            'url' => \URL::to('/'.$data->designElems['mainData']['url'])
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection
{{-- Content --}}

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <!--begin::Form-->
    <form method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/create') }}">
        @csrf
        <div class="card-body">
            <input type="hidden" name="status">
            <div class="form-group">
                <label>{{ trans('main.messageType') }} :</label>                            
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="message_type">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="1" {{ old('message_type') == 1 ? 'selected' : '' }}>{{ trans('main.equal') }}</option>
                    <option value="2" {{ old('message_type') == 2 ? 'selected' : '' }}>{{ trans('main.part') }}</option>
                </select>
            </div> 
            <div class="form-group">
                <label>{{ trans('main.clientMessage') }} :</label>
                <input class="form-control" type="text" value="{{ old('message') }}" name="message" placeholder="{{ trans('main.clientMessage') }}">
            </div>
            <div class="form-group">
                <label>{{ trans('main.replyType') }} :</label>
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="reply_type">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="1" {{ old('reply_type') == 1 ? 'selected' : '' }}>{{ trans('main.text') }}</option>
                    <option value="2" {{ old('reply_type') == 2 ? 'selected' : '' }}>{{ trans('main.botPhoto') }}</option>
                    <option value="3" {{ old('reply_type') == 3 ? 'selected' : '' }}>{{ trans('main.video') }}</option>
                    <option value="4" {{ old('reply_type') == 4 ? 'selected' : '' }}>{{ trans('main.sound') }}</option>
                    <option value="5" {{ old('reply_type') == 5 ? 'selected' : '' }}>{{ trans('main.file') }}</option>
                    <option value="8" {{ old('reply_type') == 8 ? 'selected' : '' }}>{{ trans('main.mapLocation') }}</option>
                    <option value="9" {{ old('reply_type') == 9 ? 'selected' : '' }}>{{ trans('main.whatsappNos') }}</option>
                    <option value="10" {{ old('reply_type') == 10 ? 'selected' : '' }}>{{ trans('main.disappearing') }}</option>
                    <option value="11" {{ old('reply_type') == 11 ? 'selected' : '' }}>{{ trans('main.mention') }}</option>
                    <option value="16" {{ old('reply_type') == 16 ? 'selected' : '' }}>{{ trans('main.link') }}</option>
                    <option value="50" {{ old('reply_type') == 50 ? 'selected' : '' }}>{{ trans('main.webhook') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label> {{ trans('main.lang') }}</label>                            
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="lang">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="0">{{ trans('main.arabic') }}</option>
                    <option value="1">{{ trans('main.english') }}</option>
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
            {{-- Webhook Messages Design --}}
            <div class="reply hidden" data-id="50">
                <div class="form-group">
                    <label> {{ trans('main.webhookURL') }}</label>            
                    <input class="form-control" type="text" value="{{ old('webhook_url') }}" name="webhook_url" placeholder="{{ trans('main.webhookURL') }}">
                </div>
                <div class="form-group">
                    <label> {{ trans('main.sentTemplates') }}</label>            
                    @foreach($data->templates as $template)
                    <label class="checkbox checkbox-outline checkbox-primary">
                        <input type="checkbox" name="templates[]"  value="{{ $template->id }}"/>
                        <span></span>
                        {{ $template->title }} 
                    </label>
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
<script src="{{ asset('assets/tenant/components/addBot.js') }}"></script>
@endsection