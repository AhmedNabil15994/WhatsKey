{{-- Extends layout --}}
@extends('tenant.Layouts.V5.master2')
@section('title',$data->designElems['mainData']['title'])
@section('styles')
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
@section('content')
<select name="bots" class="hidden">
    @foreach($data->bots as $bot)
    <option value="{{ $bot->id }}" data-type="1">{{ trans('main.clientMessage') . ' ( ' .$bot->message . ' ) ==== ' . trans('main.bot') }}</option>
    @endforeach
    @foreach($data->botPlus as $plusBot)
    <option value="{{ $plusBot->id }}" data-type="2">{{ trans('main.clientMessage') . ' ( ' .$plusBot->message . ' ) ==== ' . trans('main.botPlus') }}</option>
    @endforeach
</select>
<!-- Start Content-->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="form">
                <div class="card-body">
                    <form class="formPayment" method="POST" action="{{ URL::to('/templateMsg/create') }}">
                        @csrf
                        <input type="hidden" name="status">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="titleLabel">{{ trans('main.messageType') }} :</label>                            
                            </div>
                            <div class="col-md-9">
                                <div class="selectStyle">
                                    <select data-toggle="select2" data-style="btn-outline-myPR" name="message_type">
                                        <option value="">{{ trans('main.choose') }}</option>
                                        <option value="1" {{ old('message_type') == 1 ? 'selected' : '' }}>{{ trans('main.equal') }}</option>
                                        <option value="2" {{ old('message_type') == 2 ? 'selected' : '' }}>{{ trans('main.part') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.clientMessage') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" value="{{ old('message') }}" name="message" placeholder="{{ trans('main.clientMessage') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.title') }} :</label>
                            </div>
                            <div class='col-md-9'>
                                <select data-toggle='select2' name='title_type'>
                                    <option value='1' selected>{{ trans('main.text') }}</option>
                                    <option value='2'>{{ trans('main.image') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="row textRow">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.title') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" value="{{ old('title') }}" name="title" placeholder="{{ trans('main.title') }}">
                            </div>
                        </div>
                        <div class="row imageRow hidden">
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
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.body') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <textarea name="body" placeholder="{{ trans('main.body') }}">{{ old('body') }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.footer') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" value="{{ old('footer') }}" name="footer" placeholder="{{ trans('main.footer') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="inputPassword3" class="titleLabel">{{ trans('main.buttons') }} :</label>
                            </div>
                            <div class="col-md-9">
                                <select data-toggle="select2" data-style="btn-outline-myPR" name="buttons">
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
                            </div>
                            <div class="clearfix"></div>
                            <div class="buts">
                                <div class='row mains'>
                                    <div class='col-md-3'>
                                        <label class='titleLabel'>{{ trans('main.btnData',['button'=>1]) }} :</label>
                                    </div>
                                    <div class='col-md-9'>
                                        <div class='row'>
                                            <div class='col-md-3'>
                                                <input type='text' name='btn_text_1' value="" placeholder='{{ trans('main.text') }}'>
                                            </div>
                                            <div class='col-md-3'>
                                                <select data-toggle='select2' class='button_types' name='btn_type_1'>
                                                    <option value='1' selected>{{ trans('main.urlButton') }}</option>
                                                    <option value='2'>{{ trans('main.callButton') }}</option>
                                                    <option value='3'>{{ trans('main.normalButton') }}</option>
                                                </select>
                                            </div>
                                            <div class='col-md-6 repy'>
                                                <input type="text" name="url_1" placeholder='{{ trans('main.url') }}'>
                                                <select data-toggle='' class='reply_types hidden' name='btn_reply_type_1'>
                                                    <option value='1' selected>{{ trans('main.newReply') }}</option>
                                                    <option value='2'>{{ trans('main.botMsg') }}</option>
                                                </select>
                                                <textarea class="hidden" name='btn_reply_1' placeholder='{{ trans('main.messageContent') }}' maxlength="140"></textarea>
                                                <select data-toggle="" class='dets hidden' name='btn_msg_1'>
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
                        <hr class="mt-5">
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <div class="nextPrev clearfix ">
                                    <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" type="reset" class="btn btnNext Reset">{{ trans('main.back') }}</a>
                                    <button name="Submit" type="submit" class="btnNext AddBTN" id="SubmitBTN">{{ trans('main.add') }}</button>
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
@endsection

@section('topScripts')
<script src="{{ asset('V5/components/addTemplateMsg.js') }}"></script>
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection