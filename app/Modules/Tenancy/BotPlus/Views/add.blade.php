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
            <div class="form-group">
                <label>{{ trans('main.clientMessage') }} :</label>
                <input class="form-control" type="text" value="{{ old('message') }}" name="message" placeholder="{{ trans('main.clientMessage') }}">
            </div>
            <div class="form-group">
                <label>{{ trans('main.title') }} :</label>
                <select class="form-control" data-toggle='select2' name='title_type'>
                    <option value='1' selected>{{ trans('main.text') }}</option>
                    <option value='2'>{{ trans('main.image') }}</option>
                </select>
            </div>
            <div class="form-group textRow">
                <label>{{ trans('main.title') }} :</label>
                <input class="form-control" type="text" value="{{ old('title') }}" name="title" placeholder="{{ trans('main.title') }}">
            </div>
            <div class="form-group imageRow hidden">
                <label>{{ trans('main.image') }} :</label>
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
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.add')}}</button>
                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/tenant/components/addBotPlus.js') }}"></script>
@endsection