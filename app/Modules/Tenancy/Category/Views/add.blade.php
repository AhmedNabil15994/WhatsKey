{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="screen">
    .select2-container--default .select2-results__option[aria-selected=true],
    .select2-container--default .select2-results__option--highlighted[aria-selected]{
        background-color: unset;
    }
    .select2-results ul li:not(:nth-child(1)){color: #FFF !important;}
    .select2-results ul li:nth-child(2){background-color: #A52C71 !important;}
    .select2-results ul li:nth-child(3){background-color: #8FA840 !important;}
    .select2-results ul li:nth-child(4){background-color: #C1A03F !important;}
    .select2-results ul li:nth-child(5){background-color: #772237 !important;}
    .select2-results ul li:nth-child(6){background-color: #AC8671 !important;}
    .select2-results ul li:nth-child(7){background-color: #EFB32F !important;}
    .select2-results ul li:nth-child(8){background-color: #B4B227 !important;}
    .select2-results ul li:nth-child(9){background-color: #C79DCD !important;}
    .select2-results ul li:nth-child(10){background-color: #8B6890 !important;}
    .select2-results ul li:nth-child(11){background-color: #FF898D !important;}
    .select2-results ul li:nth-child(12){background-color: #54C166 !important;}
    .select2-results ul li:nth-child(13){background-color: #FF7B6C !important;}
    .select2-results ul li:nth-child(14){background-color: #28C4DB !important;}
    .select2-results ul li:nth-child(15){background-color: #56C9FF !important;}
    .select2-results ul li:nth-child(16){background-color: #72666A !important;}
    .select2-results ul li:nth-child(17){background-color: #7D8FA5 !important;}
    .select2-results ul li:nth-child(18){background-color: #5796FF !important;}
    .select2-results ul li:nth-child(19){background-color: #6C267E !important;}
    .select2-results ul li:nth-child(20){background-color: #7ACCA4 !important;}
    .select2-results ul li:nth-child(21){background-color: #23353F !important;}
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
            'title' => trans('main.'.$data->designElems['mainData']['name']),
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
            @foreach($data->designElems['modelData'] as $propKey => $propValue)
            <div class="form-group">
                <label>{{ $propValue['label'] }} 
                    @if(isset($propValue['required']) && $propValue['required'] == true )<span class="text-danger">*</span>@endif
                </label>
               
                @if(in_array($propValue['type'], ['email','text','number','password','tel']))
                @if($propValue['type'] == 'tel')
                <input type="hidden" name="phone">
                @endif
                <input class="form-control {{ $propValue['class'] }}" {{ $propValue['specialAttr'] }} type="{{ $propValue['type'] }}" name="{{ $propKey }}" value="{{ old($propKey) }}" placeholder="{{ $propValue['label'] }}" {{ $propValue['type'] == 'tel' ? "dir=ltr" : '' }}>
                @endif

                @if($propValue['type'] == 'textarea')
                    <textarea {{ $propValue['specialAttr'] }} name="{{ $propKey }}" class="form-control {{ $propValue['class'] }}" placeholder="{{ $propValue['label'] }}">{{ old($propKey) }}</textarea>
                @endif

                @if($propValue['type'] == 'select')
                    <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="{{ $propKey }}">
                        <option value="">{{ trans('main.choose') }}</option>
                        @foreach($propValue['options'] as $option)
                        @php $option = (object) $option; @endphp
                        <option value="{{ $option->id }}" {{ old($propKey) == $option->id ? 'selected' : '' }} {{ Session::has($propKey) && Session::get($propKey) == $option->id ? 'selected' : '' }}>{{ $option->title }}</option>
                        @endforeach
                    </select>
                @endif

                @if($propValue['type'] == 'image' && \Helper::checkRules('uploadImage-'.$data->designElems['mainData']['nameOne']))
                    <div class="dropzone dropzone-default" id="kt_dropzone_1">
                        <div class="dropzone-msg dz-message needsclick">
                            <h3 class="dropzone-msg-title">{{ trans('main.dropzoneP') }}</h3>
                        </div>
                    </div>
                @endif
            </div>
            @endforeach

            @if($data->designElems['mainData']['url'] == 'groups' || $data->designElems['mainData']['url'] == 'users')
            <div class="form-group">
                <label>{{ $data->designElems['mainData']['url'] == 'users' ? trans('main.extraPermissions') : trans('main.permissions')}}</label>
                <select class="form-control select2" id="kt_select2_3" name="permission[]" multiple="multiple">
                @foreach($data->permissions as $key => $permission)
                <optgroup label="{{ trans('main.'.lcfirst(str_replace('Controllers','',$key))) }}">
                    @foreach($permission as $one => $onePerm)
                    <option value="{{ $onePerm['perm_name'] }}">{{ $onePerm['perm_title'] }}</option>
                    @endforeach
                </optgroup>
                @endforeach
                </select>
            </div>
            @endif
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.add')}}</button>
                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>

 
@endsection

@section('scripts')
<script src="{{asset('assets/tenant/js/pages/crud/forms/widgets/select2.js')}}"></script>
@endsection
