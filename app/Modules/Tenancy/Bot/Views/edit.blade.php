{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/tenant/css/photoswipe.css') }}" />
@endsection
@section('breadcrumbs')
@include('tenant.Layouts.breadcrumb',[
    'breadcrumbs' => [
        [
            'title' => trans('main.dashboard'),
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
    <form method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/update/'.$data->data->id) }}">
        @csrf
        <input type="hidden" name="status" value="{{ $data->data->status }}">
        <div class="card-body">
            <div class="form-group">
                <label>{{ trans('main.messageType') }} :</label>                            
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="message_type">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="1" {{ $data->data->message_type == 1 ? 'selected' : '' }}>{{ trans('main.equal') }}</option>
                                        <option value="2" {{ $data->data->message_type == 2 ? 'selected' : '' }}>{{ trans('main.part') }}</option>
                </select>
            </div> 
            <div class="form-group">
                <label>{{ trans('main.clientMessage') }} :</label>
                <input class="form-control" type="text" value="{{ $data->data->message }}" name="message" placeholder="{{ trans('main.clientMessage') }}">
            </div>
            <div class="form-group">
                <label>{{ trans('main.replyType') }} :</label>
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="reply_type">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="1" {{ $data->data->reply_type == 1 ? 'selected' : '' }}>{{ trans('main.text') }}</option>
                    <option value="2" {{ $data->data->reply_type == 2 ? 'selected' : '' }}>{{ trans('main.botPhoto') }}</option>
                    <option value="3" {{ $data->data->reply_type == 3 ? 'selected' : '' }}>{{ trans('main.video') }}</option>
                    <option value="4" {{ $data->data->reply_type == 4 ? 'selected' : '' }}>{{ trans('main.sound') }}</option>
                    <option value="5" {{ $data->data->reply_type == 5 ? 'selected' : '' }}>{{ trans('main.file') }}</option>
                    <option value="8" {{ $data->data->reply_type == 8 ? 'selected' : '' }}>{{ trans('main.mapLocation') }}</option>
                    <option value="9" {{ $data->data->reply_type == 9 ? 'selected' : '' }}>{{ trans('main.whatsappNos') }}</option>
                    <option value="10" {{ $data->data->reply_type == 10 ? 'selected' : '' }}>{{ trans('main.disappearing') }}</option>
                    <option value="11" {{ $data->data->reply_type == 11 ? 'selected' : '' }}>{{ trans('main.mention') }}</option>
                    <option value="16" {{ $data->data->reply_type == 16 ? 'selected' : '' }}>{{ trans('main.link') }}</option>
                    <option value="50" {{ $data->data->reply_type == 50 ? 'selected' : '' }}>{{ trans('main.webhook') }}</option>
                </select>
            </div>
            <div class="form-group">
                <label> {{ trans('main.lang') }}</label>                            
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="lang">
                    <option value="">{{ trans('main.choose') }}</option>
                    <option value="0" {{ $data->data->lang == 0 ? 'selected' : '' }}>{{ trans('main.arabic') }}</option>
                    <option value="1" {{ $data->data->lang == 1 ? 'selected' : '' }}>{{ trans('main.english') }}</option>
                </select>
            </div>


            {{-- Text Messages Design --}}
            <div class="reply {{$data->data->reply_type == 1 ? '' : 'hidden'}}" data-id="1">
                <div class="form-group">
                    <label> {{ trans('main.messageContent') }}</label>                   
                    <textarea name="replyText" class="form-control" placeholder="{{ trans('main.messageContent') }}">{{ $data->data->reply_type == 1 ?  $data->data->reply : '' }}</textarea>
                </div> 
            </div>
            {{-- Photo Messages Design --}}
            <div class="reply {{$data->data->reply_type == 2 ? '' : 'hidden'}}" data-id="2">
                <div class="form-group">
                    <label> {{ trans('main.caption') }}</label>            
                    <input type="text" class="form-control" name="reply" placeholder="{{ trans('main.caption') }}" value="{{ $data->data->reply }}">
                </div>
                <div class="form-group">
                    <label class="titleLabel" for="attachFile"> {{ trans('main.attachFile') }}</label>   
                    <div class="dropzone kt_dropzone_bot_2">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <div class="dz-message needsclick">
                            <i class="h1 si si-cloud-upload"></i>
                            <h3>{{ trans('main.dropzoneP') }}</h3>
                        </div>
                        @if($data->data->file != '')
                        <div class="dz-preview dz-image-preview" id="my-preview">  
                            <div class="dz-image">
                                <img alt="image" src="{{ $data->data->file }}">
                            </div>  
                            <div class="dz-details">
                                <div class="dz-size">
                                    <span><strong>{{ $data->data->file_size }}</strong></span>
                                </div>
                                <div class="dz-filename">
                                    <span data-dz-name="">{{ $data->data->file_name }}</span>
                                </div>
                                <div class="PhotoBTNS">
                                    <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                       <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                            <a href="{{ $data->data->file }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                            <img src="{{ $data->data->file }}" itemprop="thumbnail" style="display: none;">
                                        </figure>
                                    </div>
                                    @if(\Helper::checkRules('deleteImage-'.$data->designElems['mainData']['nameOne']))
                                    <a class="DeletePhotoBot" data-area="{{ $data->data->id }}"><i class="fa fa-trash" data-name="{{ $data->data->file_name }}" data-clname="Photo"></i> </a>
                                    @endif                    
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Video Messages Design --}}
            <div class="reply {{$data->data->reply_type == 3 ? '' : 'hidden'}}" data-id="3">
                <div class="form-group">
                    <label> {{ trans('main.caption') }}</label>            
                    <input type="text" class="form-control" name="caption" placeholder="{{ trans('main.caption') }}" value="{{ $data->data->reply }}">
                </div>
                <div class="form-group">
                    <label class="titleLabel" for="attachFile"> {{ trans('main.attachFile') }}</label>   
                    <div class="dropzone kt_dropzone_bot_2">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <div class="dz-message needsclick">
                            <i class="h1 si si-cloud-upload"></i>
                            <h3>{{ trans('main.dropzoneP') }}</h3>
                        </div>
                        @if($data->data->file != '')
                        <div class="dz-preview dz-image-preview" id="my-preview">  
                            <div class="dz-image">
                                <img alt="image" src="{{ $data->data->file }}">
                            </div>  
                            <div class="dz-details">
                                <div class="dz-size">
                                    <span><strong>{{ $data->data->file_size }}</strong></span>
                                </div>
                                <div class="dz-filename">
                                    <span data-dz-name="">{{ $data->data->file_name }}</span>
                                </div>
                                <div class="PhotoBTNS">
                                    <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                       <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                            <a href="{{ $data->data->file }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                            <img src="{{ $data->data->file }}" itemprop="thumbnail" style="display: none;">
                                        </figure>
                                    </div>
                                    @if(\Helper::checkRules('deleteImage-'.$data->designElems['mainData']['nameOne']))
                                    <a class="DeletePhotoBot" data-area="{{ $data->data->id }}"><i class="fa fa-trash" data-name="{{ $data->data->file_name }}" data-clname="Photo"></i> </a>
                                    @endif                    
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Sound Messages Design --}}
            <div class="reply {{$data->data->reply_type == 4 ? '' : 'hidden'}}" data-id="4">
                <div class="form-group">
                    <label class="titleLabel" for="attachFile"> {{ trans('main.attachFile') }}</label>   
                    <div class="dropzone kt_dropzone_bot_2">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <div class="dz-message needsclick">
                            <i class="h1 si si-cloud-upload"></i>
                            <h3>{{ trans('main.dropzoneP') }}</h3>
                        </div>
                        @if($data->data->file != '')
                        <div class="dz-preview dz-image-preview" id="my-preview">  
                            <div class="dz-image">
                                <img alt="image" src="{{ $data->data->file }}">
                            </div>  
                            <div class="dz-details">
                                <div class="dz-size">
                                    <span><strong>{{ $data->data->file_size }}</strong></span>
                                </div>
                                <div class="dz-filename">
                                    <span data-dz-name="">{{ $data->data->file_name }}</span>
                                </div>
                                <div class="PhotoBTNS">
                                    <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                       <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                            <a href="{{ $data->data->file }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                            <img src="{{ $data->data->file }}" itemprop="thumbnail" style="display: none;">
                                        </figure>
                                    </div>
                                    @if(\Helper::checkRules('deleteImage-'.$data->designElems['mainData']['nameOne']))
                                    <a class="DeletePhotoBot" data-area="{{ $data->data->id }}"><i class="fa fa-trash" data-name="{{ $data->data->file_name }}" data-clname="Photo"></i> </a>
                                    @endif                    
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Document Messages Design --}}
            <div class="reply {{$data->data->reply_type == 5 ? '' : 'hidden'}}" data-id="5">
                <div class="form-group">
                    <label class="titleLabel" for="attachFile"> {{ trans('main.attachFile') }}</label>   
                    <div class="dropzone kt_dropzone_bot_2">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <div class="dz-message needsclick">
                            <i class="h1 si si-cloud-upload"></i>
                            <h3>{{ trans('main.dropzoneP') }}</h3>
                        </div>
                        @if($data->data->file != '')
                        <div class="dz-preview dz-image-preview" id="my-preview">  
                            <div class="dz-image">
                                <img alt="image" src="{{ $data->data->file }}">
                            </div>  
                            <div class="dz-details">
                                <div class="dz-size">
                                    <span><strong>{{ $data->data->file_size }}</strong></span>
                                </div>
                                <div class="dz-filename">
                                    <span data-dz-name="">{{ $data->data->file_name }}</span>
                                </div>
                                <div class="PhotoBTNS">
                                    <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                       <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                            <a href="{{ $data->data->file }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                            <img src="{{ $data->data->file }}" itemprop="thumbnail" style="display: none;">
                                        </figure>
                                    </div>
                                    @if(\Helper::checkRules('deleteImage-'.$data->designElems['mainData']['nameOne']))
                                    <a class="DeletePhotoBot" data-area="{{ $data->data->id }}"><i class="fa fa-trash" data-name="{{ $data->data->file_name }}" data-clname="Photo"></i> </a>
                                    @endif                    
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- Location Messages Design --}}
            <div class="reply {{$data->data->reply_type == 8 ? '' : 'hidden'}}" data-id="8">
                <div class="form-group">
                    <label> {{ trans('main.lat') }}</label>            
                    <input class="form-control" type="text" value="{{ $data->data->lat }}" name="lat" placeholder="{{ trans('main.lat') }}">
                </div>
                <div class="form-group">
                    <label> {{ trans('main.lng') }}</label>            
                    <input class="form-control" type="text" value="{{ $data->data->lng }}" name="lng" placeholder="{{ trans('main.lng') }}">
                </div>
                <div class="form-group">
                    <label> {{ trans('main.location') }}</label>            
                    <input class="form-control" type="text" value="{{ $data->data->address }}" name="address" placeholder="{{ trans('main.location') }}">
                </div>
            </div>
            {{-- Contact Messages Design --}}
            <div class="reply {{$data->data->reply_type == 9 ? '' : 'hidden'}}" data-id="9">
                <div class="form-group">
                    <label> {{ trans('main.whatsappNo') }}</label>            
                    <input type="hidden" name="phone1">
                    <input class="form-control" type="tel" class="form-control" dir="ltr" id="telephone1" value="{{ $data->data->whatsapp_no }}" name="whatsapp_no" placeholder="{{ trans('main.whatsappNo') }}">
                </div>
            </div>
            {{-- Disappearing Messages Design --}}
            <div class="reply {{$data->data->reply_type == 10 ? '' : 'hidden'}}" data-id="10">
                <div class="form-group">
                    <label> {{ trans('main.messageContent') }}</label>            
                    <textarea class="form-control" name="disappearingText" placeholder="{{ trans('main.messageContent') }}">{{ $data->data->reply }}</textarea>
                </div>
                <div class="form-group">
                    <label> {{ trans('main.expires_in') }} ( {{trans('main.minute')}} )</label>            
                    <input class="form-control" type="tel" value="{{ $data->data->expiration_in_seconds }}" name="expires_in" placeholder="{{ trans('main.expires_in') }}">
                </div>                
            </div>
            {{-- Mention Messages Design --}}
            <div class="reply {{$data->data->reply_type == 11 ? '' : 'hidden'}}" data-id="11">
                <div class="form-group">
                    <label> {{ trans('main.mention') }}</label>            
                    <input type="hidden" name="phone2">
                    <input class="form-control" type="tel" class="form-control" dir="ltr" id="telephone2" value="{{ $data->data->mention }}" name="mention" placeholder="{{ trans('main.mention') }}">
                </div>
            </div>
            {{-- Link Messages Design --}}
            <div class="reply {{$data->data->reply_type == 16 ? '' : 'hidden'}}" data-id="16">
                <div class="form-group">
                    <label> {{ trans('main.url') }}</label>            
                    <input class="form-control" type="text" value="{{ $data->data->https_url }}" name="https_url" placeholder="{{ trans('main.url') }}">
                </div>
                <div class="form-group">
                    <label> {{ trans('main.urlTitle') }}</label>            
                    <input class="form-control" type="text" value="{{ $data->data->url_title }}" name="url_title" placeholder="{{ trans('main.urlTitle') }}">
                </div>
                <div class="form-group">
                    <label> {{ trans('main.urlDesc') }}</label>            
                    <input class="form-control" type="text" value="{{ $data->data->url_desc }}" name="url_desc" placeholder="{{ trans('main.urlDesc') }}">
                </div>
            </div>
            {{-- Webhook Messages Design --}}
            <div class="reply {{$data->data->reply_type == 50 ? '' : 'hidden'}}" data-id="50">
                <div class="form-group">
                    <label> {{ trans('main.webhookURL') }}</label>            
                    <input class="form-control" type="text" value="{{ $data->data->webhook_url }}" name="webhook_url" placeholder="{{ trans('main.webhookURL') }}">
                </div>
                <div class="form-group">
                    <label> {{ trans('main.sentTemplates') }}</label>            
                    @foreach($data->templates as $template)
                    <label class="checkbox checkbox-outline checkbox-primary">
                        <input type="checkbox" name="templates[]" {{ $data->data->reply_type == 50 && in_array($template->id,$data->data->templates) ? 'checked' : '' }}  value="{{ $template->id }}"/>
                        <span></span>
                        {{ $template->title }} 
                    </label>
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
<script src="{{ asset('assets/tenant/components/addBot.js') }}"></script>
@endsection
