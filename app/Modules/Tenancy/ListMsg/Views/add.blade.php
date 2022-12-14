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
                <label>{{ trans('main.buttonText') }} :</label>
                <input class="form-control" type="text" value="{{ old('buttonText') }}" name="buttonText" placeholder="{{ trans('main.buttonText') }}">
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
                    <div class='form-group mains'>
                        <label>{{ trans('main.sectionData',['section'=>1]) }} :</label>
                        <div class='optionsRow'>
                            <div class='row'>
                                <div class='col-md-6'>
                                    <div class="form-group">
                                        <label>{{ trans('main.title') }} :</label>
                                        <input class="form-control" type='text' name='title_1' value="" placeholder='{{ trans('main.title') }}'>
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
                                    <input type="text" class="form-control" name="item_title_1_1" placeholder='{{ trans('main.title') }}'>
                                </div>
                                <div class="col-md-3">
                                    <textarea class="form-control" name='item_description_1_1' placeholder='{{ trans('main.desc') }}' maxlength="140"></textarea>
                                </div>
                                <div class="col-md-3">
                                    <select data-toggle='select2' class='reply_types form-control' name='item_reply_type_1_1'>
                                        <option value='1' selected>{{ trans('main.newReply') }}</option>
                                        <option value='2'>{{ trans('main.botMsg') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <textarea name='btn_reply_1_1' class="replyText form-control" placeholder='{{ trans('main.messageContent') }}' maxlength="140"></textarea>

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
@endsection