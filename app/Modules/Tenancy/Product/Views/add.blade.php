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
            'title' => trans('main.products'),
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
            <div class="form-group">
                <label> {{ trans('main.name') }}</label>            
                <input class="form-control" type="text" value="{{ old('name') }}" name="name" placeholder="{{ trans('main.name') }}">
            </div>
            <div class="form-group">
                <label> {{ trans('main.description') }}</label>   
                <textarea class="form-control" name="description" placeholder="{{trans('main.description')}}">{{ old('description') }}</textarea>         
            </div>
            <div class="form-group">
                <label> {{ trans('main.price') }}</label>            
                <input class="form-control" type="tel" value="{{ old('price') }}" name="price" placeholder="{{ trans('main.price') }}">
            </div>
            <div class="form-group">
                <label> {{ trans('main.currency') }}</label>            
                <select name="currency" data-toggle="select2" class="form-control">
                    <option value="">{{trans('main.choose')}}</option>
                    <option value="EGP" {{old('currency') == "EGP" ? 'selected' : ''}} >EGP</option>
                    <option value="SAR" {{old('currency') == "SAR" ? 'selected' : ''}} >SAR</option>
                    <option value="USD" {{old('currency') == "USD" ? 'selected' : ''}} >USD</option>
                </select>
            </div>
            <div class="form-group">
                <label> {{ trans('main.isHidden') }}</label>            
                <select name="is_hidden" data-toggle="select2" class="form-control">
                    <option value="">{{trans('main.choose')}}</option>
                    <option value="0" {{old('is_hidden') == 0 ? 'selected' : ''}} >{{trans('main.no')}}</option>
                    <option value="1" {{old('is_hidden') == 1 ? 'selected' : ''}} >{{trans('main.yes')}}</option>
                </select>
            </div>

            <div class="form-group">
                <label class="titleLabel" for="attachFile"> {{ trans('main.image') }}</label>   
                <div class="dropzone dropzone-default" id="kt_dropzone_1">
                    <div class="dropzone-msg dz-message needsclick">
                        <h3 class="dropzone-msg-title">{{ trans('main.dropzoneP') }}</h3>
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
<script src=""></script>
@endsection