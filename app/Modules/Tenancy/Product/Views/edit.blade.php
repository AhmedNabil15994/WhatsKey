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
    <form method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/update/'.$data->data->id) }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label> {{ trans('main.name') }}</label>            
                <input class="form-control" type="text" value="{{ $data->data->name }}" name="name" placeholder="{{ trans('main.name') }}">
            </div>
            <div class="form-group">
                <label> {{ trans('main.description') }}</label>   
                <textarea class="form-control" name="description" placeholder="{{trans('main.description')}}">{{ $data->data->description }}</textarea>         
            </div>
            <div class="form-group">
                <label> {{ trans('main.price') }}</label>            
                <input class="form-control" type="tel" value="{{ $data->data->price }}" name="price" placeholder="{{ trans('main.price') }}">
            </div>
            <div class="form-group">
                <label> {{ trans('main.currency') }}</label>            
                <select name="currency" data-toggle="select2" class="form-control">
                    <option value="">{{trans('main.choose')}}</option>
                    <option value="EGP" {{ $data->data->currency == "EGP" ? 'selected' : ''}} >EGP</option>
                    <option value="SAR" {{ $data->data->currency == "SAR" ? 'selected' : ''}} >SAR</option>
                    <option value="USD" {{ $data->data->currency == "USD" ? 'selected' : ''}} >USD</option>
                </select>
            </div>
            <div class="form-group">
                <label> {{ trans('main.isHidden') }}</label>            
                <select name="is_hidden" data-toggle="select2" class="form-control">
                    <option value="">{{trans('main.choose')}}</option>
                    <option value="0" {{ $data->data->is_hidden == 0 ? 'selected' : ''}} >{{trans('main.no')}}</option>
                    <option value="1" {{ $data->data->is_hidden == 1 ? 'selected' : ''}} >{{trans('main.yes')}}</option>
                </select>
            </div>

            @if(\Helper::checkRules('uploadImage-'.$data->designElems['mainData']['nameOne']))
            <div class="dropzone" id="kt_dropzone_11">
                <div class="fallback">
                    <input name="file" type="file" />
                </div>
                <div class="dz-message needsclick">
                    <i class="h1 si si-cloud-upload"></i>
                    <h3>{{ trans('main.dropzoneP') }}</h3>
                </div>
                @if($data->data->images != '')
                <div class="dz-preview dz-image-preview" id="my-preview">  
                    <div class="dz-image">
                        <img alt="image" src="{{ $data->data->images }}">
                    </div>  
                    <div class="dz-details">
                        <div class="PhotoBTNS">
                            <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                               <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                    <a href="{{ $data->data->images }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                    <img src="{{ $data->data->images }}" itemprop="thumbnail" style="display: none;">
                                </figure>
                            </div>
                            @if(\Helper::checkRules('deleteImage-'.$data->designElems['mainData']['nameOne']))
                            <a class="DeletePhoto" data-area="{{ $data->data->id }}"><i class="fa fa-trash"  data-clname="Photo"></i> </a>
                            @endif                    
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
            
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
@endsection
