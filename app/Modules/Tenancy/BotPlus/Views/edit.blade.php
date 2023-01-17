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
            <div class="form-group">
                <label>{{ trans('main.title') }} :</label>
                <select class="form-control" data-toggle='select2' name='title_type'>
                    <option value='1' {{$data->data->title_type == 1 ? 'selected' : ''}}>{{ trans('main.text') }}</option>
                    <option value='2' {{$data->data->title_type == 2 ? 'selected' : ''}}>{{ trans('main.image') }}</option>
                </select>
            </div>
            <div class="form-group textRow {{$data->data->title_type == 1 ? '' : 'hidden'}} textWrap">
                <label>{{ trans('main.title') }} :</label>
                <input class="form-control" type="text" value="{{ $data->data->title }}" name="title" placeholder="{{ trans('main.title') }}">
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group imageRow {{$data->data->title_type == 2 ? '' : 'hidden'}}">
                <label>{{ trans('main.image') }} :</label>
                <div class="dropzone kt_dropzone_1">
                    <div class="fallback">
                        <input name="file" type="file" />
                    </div>
                    <div class="dz-message needsclick">
                        <i class="h1 si si-cloud-upload"></i>
                        <h3 class="text-center">{{ trans('main.dropzoneP') }}</h3>
                    </div>
                    @if($data->data->image != '')
                    <div class="dz-preview dz-image-preview" id="my-preview">  
                        <div class="dz-image">
                            <img alt="image" src="{{ $data->data->image }}">
                        </div>  
                        <div class="dz-details">
                            <div class="dz-size">
                                <span><strong>{{ $data->data->image_size }}</strong></span>
                            </div>
                            <div class="dz-filename">
                                <span data-dz-name="">{{ $data->data->image_name }}</span>
                            </div>
                            <div class="PhotoBTNS">
                                <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                   <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                        <a href="{{ $data->data->image }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                        <img src="{{ $data->data->image }}" itemprop="thumbnail" style="display: none;">
                                    </figure>
                                </div>
                                @if(\Helper::checkRules('deleteImage-'.$data->designElems['mainData']['nameOne']))
                                <a class="DeletePhoto" data-area="{{ $data->data->id }}"><i class="fa fa-trash" data-name="{{ $data->data->image_name }}" data-clname="Photo"></i> </a>
                                @endif                    
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
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
            <div class="form-group">
                <label>{{ trans('main.buttons') }} :</label>
                <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="buttons">
                    <option value="1" {{ $data->data->buttons == 1 ? 'selected' : '' }}>1</option>
                    <option value="2" {{ $data->data->buttons == 2 ? 'selected' : '' }}>2</option>
                    <option value="3" {{ $data->data->buttons == 3 ? 'selected' : '' }}>3</option>
                    <option value="4" {{ $data->data->buttons == 4 ? 'selected' : '' }}>4</option>
                    <option value="5" {{ $data->data->buttons == 5 ? 'selected' : '' }}>5</option>
                    <option value="6" {{ $data->data->buttons == 6 ? 'selected' : '' }}>6</option>
                    <option value="7" {{ $data->data->buttons == 7 ? 'selected' : '' }}>7</option>
                    <option value="8" {{ $data->data->buttons == 8 ? 'selected' : '' }}>8</option>
                    <option value="9" {{ $data->data->buttons == 9 ? 'selected' : '' }}>9</option>
                    <option value="10" {{ $data->data->buttons == 10 ? 'selected' : '' }}>10</option>
                </select>
                <div class="clearfix"></div>
                <div class="buts mt-5">
                    @foreach($data->data->buttonsData as $oneItem)
                    <div class='form-group mains buttons'>
                        <label class='titleLabel'>{{ trans('main.btnData',['button'=>$oneItem['id']]) }} :</label>
                        <div class='row'>
                            <div class='col-md-4'>
                                <div class="form-group textWrap">
                                    <input class="form-control" type='text' name='btn_text_{{ $oneItem['id'] }}' value="{{ $oneItem['text'] }}" placeholder='{{ trans('main.text') }}'>
                                    <i class="la la-smile icon-xl emoji-icon"></i>
                                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                </div>
                            </div>
                            <div class='col-md-4'>
                                <select class="form-control reply_types" data-toggle='select2' name='btn_reply_type_{{ $oneItem['id'] }}'>
                                    <option value='1' {{ $oneItem['reply_type'] == 1 ? 'selected' : '' }}>{{ trans('main.newReply') }}</option>
                                    <option value='2' {{ $oneItem['reply_type'] == 2 ? 'selected' : '' }}>{{ trans('main.botMsg') }}</option>
                                </select>
                            </div>
                            <div class='col-md-4 repy'>
                                <div class="form-group textWrap {{ $oneItem['msg_type'] == 0 ? '' : 'hidden'  }}">
                                    <textarea class="form-control" name='btn_reply_{{ $oneItem['id'] }}' placeholder='{{ trans('main.messageContent') }}' maxlength="140">{{ $oneItem['msg_type'] == 0 ? $oneItem['msg'] : ''  }}</textarea>
                                    <i class="la la-smile icon-xl emoji-icon"></i>
                                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                                </div>
                                <select data-toggle="{{ $oneItem['msg_type'] > 0 ? 'select2' : ''  }}" class='form-control dets {{ $oneItem['msg_type'] > 0 ? '' : 'hidden'  }}' name='btn_msg_{{ $oneItem['id'] }}'>
                                    <option value='' selected>{{ trans('main.choose') }}</optin>
                                    
                                    @foreach($data->bots as $bot)
                                    <option value="{{ $bot->id }}" data-type="1" {{ $oneItem['msg_type'] == 1 && isset($oneItem['msg']) && $oneItem['msg'] == $bot->id ? 'selected' : '' }}>{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.classicBot') }}</option>
                                    @endforeach

                                    @foreach($data->botPlus as $plusBot)
                                    @if($plusBot->id != $data->data->id)
                                    <option value="{{ $plusBot->id }}" data-type="2" {{ $oneItem['msg_type'] == 2 && isset($oneItem['msg'])  && $oneItem['msg'] == $plusBot->id ? 'selected' : '' }}>{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.smartBot') }}</option>
                                    @endif
                                    @endforeach      
                                                                  
                                </select>
                                <input type='hidden' name='btn_msg_type_{{ $oneItem['id'] }}' value='{{ $oneItem['msg_type'] }}'>
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
