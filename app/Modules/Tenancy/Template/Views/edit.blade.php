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

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <!--begin::Form-->
    <form method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/update/'.$data->data->id) }}">
        @csrf
        <div class="card-body">
            <input type="hidden" name="status" value="{{ $data->data->status }}">
            @foreach($data->designElems['modelData'] as $propKey => $propValue)
            <div class="form-group textWrap">
                <label for="exampleInputPassword1">{{ $propValue['label'] }} 
                    @if(isset($propValue['required']) && $propValue['required'] == true )<span class="text-danger">*</span>@endif
                </label>
               
                @if(in_array($propValue['type'], ['email','text','number','password','tel']))
                @if($propValue['type'] == 'tel')
                <input type="hidden" name="phone">
                <input class="form-control {{ $propValue['class'] }}" {{ $propValue['specialAttr'] }} type="{{ $propValue['type'] }}" name="{{ $propKey }}" value="{{ $propValue['type'] != 'password' ? ($propKey == 'telephone' ? '+'.$data->data->phone : $data->data->$propKey) : '' }}" placeholder="{{ $propValue['label'] }}" {{ $propValue['type'] == 'tel' ? "dir=ltr" : '' }}>
                @else
                <input class="form-control {{ $propValue['class'] }}" {{ $propValue['specialAttr'] }} type="{{ $propValue['type'] }}" name="{{ $propKey }}" value="{{ $propValue['type'] != 'password' ? $data->data->$propKey : '' }}" placeholder="{{ $propValue['label'] }}" {{ $propValue['type'] == 'tel' ? "dir=ltr" : '' }}>
                    @if($propValue['type'] == 'text' && in_array($data->designElems['mainData']['url'], ['replies','quickReplies','templates','groupNumbers','groups','users']))
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                    @endif
                @endif
                @endif

                @if($propValue['type'] == 'textarea')
                    <textarea {{ $propValue['specialAttr'] }} name="{{ $propKey }}" class="form-control {{ $propValue['class'] }}" placeholder="{{ $propValue['label'] }}">{{ $data->data->$propKey }}</textarea>
                    <i class="la la-smile icon-xl emoji-icon"></i>
                    <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                @endif

                @if($propValue['type'] == 'select')
                    <select class="form-control" data-toggle="select2" data-style="btn-outline-myPR" name="{{ $propKey }}">
                        <option value="">{{ trans('main.choose') }}</option>
                        @foreach($propValue['options'] as $group)
                        @php $group = (object) $group; @endphp
                        <option value="{{ $group->id }}" {{ $data->data->$propKey == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                        @endforeach
                    </select>
                @endif

                @if($propValue['type'] == 'image' && \Helper::checkRules('uploadImage-'.$data->designElems['mainData']['nameOne']))
                    <div class="dropzone" id="kt_dropzone_11">
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                        <div class="dz-message needsclick">
                            <i class="h1 si si-cloud-upload"></i>
                            <h3>{{ trans('main.dropzoneP') }}</h3>
                        </div>
                        @if($data->data->photo != '')
                        <div class="dz-preview dz-image-preview" id="my-preview">  
                            <div class="dz-image">
                                <img alt="image" src="{{ $data->data->photo }}">
                            </div>  
                            <div class="dz-details">
                                <div class="dz-size">
                                    <span><strong>{{ $data->data->photo_size }}</strong></span>
                                </div>
                                <div class="dz-filename">
                                    <span data-dz-name="">{{ $data->data->photo_name }}</span>
                                </div>
                                <div class="PhotoBTNS">
                                    <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                       <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                            <a href="{{ $data->data->photo }}" itemprop="contentUrl" data-size="555x370"><i class="fa fa-search"></i></a>
                                            <img src="{{ $data->data->photo }}" itemprop="thumbnail" style="display: none;">
                                        </figure>
                                    </div>
                                    @if(\Helper::checkRules('deleteImage-'.$data->designElems['mainData']['nameOne']))
                                    <a class="DeletePhoto" data-area="{{ $data->data->id }}"><i class="fa fa-trash" data-name="{{ $data->data->photo_name }}" data-clname="Photo"></i> </a>
                                    @endif                    
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                @endif
                @if($data->data->created_at != '')
                <span class="LastUpdate float-right mt-1 mb-0">{{ trans('main.created_at') }} :  {{ $data->data->created_at }}</span>
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
                    <option value="{{ $onePerm['perm_name'] }}" {{in_array($onePerm['perm_name'],( $data->designElems['mainData']['url'] == 'users' ? $data->data->extra_rules : $data->data->rulesArr)) ? 'selected' : ''}}>{{ $onePerm['perm_title'] }}</option>
                    @endforeach
                </optgroup>
                @endforeach
                </select>
            </div>
            @endif
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.edit')}}</button>
                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>
@endsection

@section('modals')
@include('tenant.Partials.photoswipe_modal')
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script src="{{asset('assets/tenant/js/pages/crud/forms/widgets/select2.js')}}"></script>
<script src="{{ asset('assets/tenant/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/tenant/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/tenant/components/myPhotoSwipe.js') }}"></script>      
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection