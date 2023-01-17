{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',trans('main.businessInfo'))
@section('pageName',trans('main.businessInfo'))

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
            'title' => trans('main.businessInfo'),
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
<div class="card card-custom formNumbers">
    <div class="card-header">
        <h3 class="card-title"><i class="la la-vcard-o icon-xl"></i> {{trans('main.businessInfo')}}</h3>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" action="{{ URL::to('/businessProfile/update') }}">
            @csrf
            <div class="card-body">
                <div class="form-group mb-5">
                    <label>{{ trans('main.phone') }} :</label>
                    <input class="form-control" name="phone" readonly value="{{$data->data['me']['phone']}}" placeholder="{{ trans('main.phone') }}">
                </div> 
                <div class="form-group mb-5 textWrap">
                    <label>{{ trans('main.name') }} :</label>
                    <input class="form-control" name="name" value="{{$data->data['me']['name']}}" placeholder="{{ trans('main.name') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div>
                <div class="form-group mb-5 textWrap">
                    <label>{{ trans('main.status') }} :</label>
                    <input class="form-control" name="status" value="{{$data->data['status']['status']}}" placeholder="{{ trans('main.status') }}">
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                </div> 
                <div class="form-group mb-5">
                    <label>{{ trans('main.image') }} :</label>
                    <div class="dropzone" id="kt_dropzone_11">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <div class="dz-message needsclick">
                            <i class="h1 si si-cloud-upload"></i>
                            <h3>{{ trans('main.dropzoneP') }}</h3>
                        </div>
                        @if(isset($data->data['image']) && $data->data['image'] != '')
                        <div class="dz-preview dz-image-preview" id="my-preview">  
                            <div class="dz-image">
                                <img alt="image" src="{{ $data->data['image'] }}">
                            </div>  
                            <div class="dz-details">
                                <div class="PhotoBTNS">
                                    <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                       <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                            <a href="{{ $data->data['image'] }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                            <img src="{{ $data->data['image'] }}" itemprop="thumbnail" style="display: none;">
                                        </figure>
                                    </div>
                                    <a class="DeletePhoto"><i class="fa fa-trash"></i> </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary mr-2">{{trans('main.edit')}}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('modals')
@include('tenant.Partials.photoswipe_modal')
@endsection


@section('scripts')
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
<script src="{{ asset('assets/tenant/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/tenant/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/tenant/components/myPhotoSwipe.js') }}"></script>      
@endsection
