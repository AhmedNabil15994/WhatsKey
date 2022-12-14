{{-- Extends layout --}}
@extends('tenant.Layouts.V5.master2')
@section('title',$data->designElems['mainData']['title'])
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/phone.css') }}">
<style type="text/css" media="screen">
    .user-langs{
        background-color: unset;
    }
    .mb-1{
        margin-bottom: 5px;
    }
    p.label-darks{
        padding: 20px !important;
        display: block;
        background: #F6CD02;
        color: #000;
    }
    .repy .select2-container,.repy textarea{
        width: 48% !important;
        display: inline-table;
        margin: 0 .5%;
    }
    html[dir="rtl"] .repy textarea{
        float: left;
    }
    html[dir="ltr"] .repy textarea{
        float: right;
    }
</style>
@endsection
@section('content')
<select name="bots" class="hidden">
    @foreach($data->bots as $bot)
    <option value="{{ $bot->id }}" data-type="1">{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.bot') }}</option>
    @endforeach
    @foreach($data->botPlus as $plusBot)
    @if($plusBot->id != $data->data->id)
    <option value="{{ $plusBot->id }}" data-type="2">{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.botPlus') }}</option>
    @endif
    @endforeach
</select>
<!-- Start Content-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="form">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <h4 class="title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{ $data->designElems['mainData']['title'] }}</h4>
                        </div>
                    </div>
                    <form class="formPayment" method="POST" action="{{ URL::to('/templateMsg/update/'.$data->data->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="{{ $data->data->status }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="titleLabel">{{ trans('main.messageType') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <div class="selectStyle">
                                    <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="message_type">
                                        <option value="">{{ trans('main.choose') }}</option>
                                        <option value="1" {{ $data->data->message_type == 1 ? 'selected' : '' }}>{{ trans('main.equal') }}</option>
                                        <option value="2" {{ $data->data->message_type == 2 ? 'selected' : '' }}>{{ trans('main.part') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-3">
                                <label class="titleLabel">{{ trans('main.clientMessage') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" value="{{ $data->data->message }}" name="message" placeholder="{{ trans('main.clientMessage') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.title') }} :</label>
                            </div>
                            <div class='col-md-9'>
                                <select data-toggle='select2' name='title_type'>
                                    <option value='1' {{ $data->data->title != null ? 'selected' : '' }}>{{ trans('main.text') }}</option>
                                    <option value='2' {{ $data->data->title == null && $data->data->image != null ? 'selected' : '' }}>{{ trans('main.image') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row textRow {{ $data->data->title == null && $data->data->image != null ? 'hidden' : '' }}">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.title') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" value="{{ $data->data->title }}" name="title" placeholder="{{ trans('main.title') }}">
                            </div>
                        </div>
                        <div class="row imageRow {{ $data->data->image != null ? '' : 'hidden' }}">
                            <div class="col-md-3">
                                <label class="titleLabel">{{ trans('main.image') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <div class="dropzone kt_dropzone_1">
                                    <div class="fallback">
                                        <input name="file" type="file" />
                                    </div>
                                    <div class="dz-message needsclick">
                                        <i class="h1 si si-cloud-upload"></i>
                                        <h3 class="text-center">{{ trans('main.dropzoneP') }}</h3>
                                    </div>
                                    @if($data->data->image != null)
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
                                                <a class="DeletePhotoN" data-type="file_name" data-area="{{ $data->data->id }}"><i class="fa fa-trash" data-name="{{ $data->data->image_name }}" data-clname="Photo"></i> </a>
                                                @endif                    
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.body') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <textarea name="body" placeholder="{{ trans('main.body') }}">{{ $data->data->body }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.footer') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" value="{{ $data->data->footer }}" name="footer" placeholder="{{ trans('main.footer') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.buttons') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <select data-toggle="select2" data-style="btn-outline-myPR" name="buttons">
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
                            </div>
                            <div class="clearfix"></div>
                            <div class="buts">
                                @foreach($data->data->buttonsData as $oneItem)
                                <div class='row mains'>
                                    <div class='col-md-3'>
                                        <label class='titleLabel'>{{ trans('main.btnData',['button'=>$oneItem['id']]) }} :</label>
                                    </div>
                                    <div class='col-md-9'>
                                        <div class='row'>
                                            <div class='col-md-3'>
                                                <input type='text' name='btn_text_{{ $oneItem['id'] }}' value="{{ $oneItem['text'] }}" placeholder='{{ trans('main.text') }}'>
                                            </div>
                                            <div class='col-md-3'>
                                                <select data-toggle='select2' class='button_types' name='btn_type_1'>
                                                    <option value='1' {{ $oneItem['button_type'] == 1 ? 'selected' : '' }}>{{ trans('main.urlButton') }}</option>
                                                    <option value='2' {{ $oneItem['button_type'] == 2 ? 'selected' : '' }}>{{ trans('main.callButton') }}</option>
                                                    <option value='3' {{ $oneItem['button_type'] == 3 ? 'selected' : '' }}>{{ trans('main.normalButton') }}</option>
                                                </select>
                                            </div>
                                            <div class='col-md-3'>
                                                
                                            </div>
                                            <div class='col-md-6 repy'>
                                                <input class="{{ $oneItem['button_type'] == 3 ? 'hidden' : '' }}" type="text" name="{{$oneItem['button_type'] == 2 ? 'contact_'.$oneItem['id'] : 'url_'.$oneItem['id'] }}" placeholder='{{ trans('main.url') }}' value="{{$oneItem['msg']}}">

                                                <select data-toggle='{{ $oneItem['button_type'] == 3 ? 'select2' : ''  }}' class='reply_types {{ $oneItem['button_type'] == 3 ? '' : 'hidden'  }}' name='btn_reply_type_{{ $oneItem['id'] }}'>
                                                    <option value='1' {{ $oneItem['reply_type'] == 1 ? 'selected' : '' }}>{{ trans('main.newReply') }}</option>
                                                    <option value='2' {{ $oneItem['reply_type'] == 2 ? 'selected' : '' }}>{{ trans('main.botMsg') }}</option>
                                                </select>

                                                <textarea class="{{ $oneItem['button_type'] == 3 ? ($oneItem['msg_type'] > 0 ? 'hidden' : '' ) : 'hidden'  }}" name='btn_reply_{{ $oneItem['id'] }}' placeholder='{{ trans('main.messageContent') }}' maxlength="140">{{ $oneItem['msg_type'] == 0 ? $oneItem['msg'] : ''  }}</textarea>

                                                <select data-toggle="{{ $oneItem['msg_type'] > 0 ? 'select2' : ''  }}" class='dets {{ $oneItem['msg_type'] > 0 ? '' : 'hidden'  }}' name='btn_msg_{{ $oneItem['id'] }}'>
                                                    <option value='' selected>{{ trans('main.choose') }}</optin>
                                                    @foreach($data->bots as $bot)
                                                    <option value="{{ $bot->id }}" data-type="1" {{ $oneItem['msg_type'] == 1 && isset($oneItem['msg']) && $oneItem['msg'] == $bot->id ? 'selected' : '' }}>{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.bot') }}</option>
                                                    @endforeach
                                                    @foreach($data->botPlus as $plusBot)
                                                    @if($plusBot->id != $data->data->id)
                                                    <option value="{{ $plusBot->id }}" data-type="2" {{ $oneItem['msg_type'] == 2 && isset($oneItem['msg'])  && $oneItem['msg'] == $plusBot->id ? 'selected' : '' }}>{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.botPlus') }}</option>
                                                    @endif
                                                    @endforeach
                                                </select>
                                                <input type='hidden' name='btn_msg_type_{{ $oneItem['id'] }}' value='{{ $oneItem['msg_type'] }}'>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4 class="title"> {{ trans('main.actions') }}</h4>
                            </div>
                        </div>
                        <div class="form-group row mt-3">
                            <div class="col-md-3">
                                <label class="titleLabel">{{ trans('main.assignLabel') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <div class="selectStyle">
                                    <select data-toggle="select2" data-style="btn-outline-myPR" name="category_id">
                                        <option value="" >{{ trans('main.categories') }}</option>
                                        @foreach($data->labels as $label)
                                        <option value="{{ $label->id }}" {{ $label->id == $data->data->category_id ? 'selected' : '' }}>{{ $label->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label class="titleLabel">{{ trans('main.assignMod') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <div class="selectStyle">
                                    <select data-toggle="select2" data-style="btn-outline-myPR" name="moderator_id">
                                        <option value="" >{{ trans('main.mods') }}</option>
                                        @foreach($data->mods as $mod)
                                        <option value="{{ $mod->id }}" {{ $mod->id == $data->data->moderator_id ? 'selected' : '' }}>{{ $mod->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <hr class="mt-5">
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <div class="nextPrev clearfix ">
                                    <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" type="reset" class="btn btnNext Reset">{{ trans('main.back') }}</a>
                                    <button name="Submit" type="submit" class="btnNext AddBTN" id="SubmitBTN">{{ trans('main.edit') }}</button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </form>
                    <!--end: Datatable-->
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>
    <!-- end row-->
</div> <!-- container -->
<div class="d-none" id="uploadPreviewTemplate">
    <div class="card mt-1 mb-0 shadow-none border">
        <div class="p-2">
            <div class="row align-items-center">
                <div class="col-auto">
                    <img data-dz-thumbnail="" src="#" class="avatar-sm rounded bg-light" alt="">
                </div>
                <div class="col pl-0">
                    <a href="javascript:void(0);" class="text-muted font-weight-bold" data-dz-name=""></a>
                    <p class="mb-0" data-dz-size=""></p>
                </div>
                <div class="col-auto">
                    <!-- Button -->
                    <a href="" class="btn btn-link btn-lg text-muted" data-dz-remove="">
                        <i class="dripicons-cross"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
@include('tenant.Partials.photoswipe_modal')
@endsection

@section('scripts')
<script src="{{ asset('V5/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('V5/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('V5/components/myPhotoSwipe.js') }}"></script>      
<script src="{{ asset('V5/components/addTemplateMsg.js') }}"></script>
@endsection
