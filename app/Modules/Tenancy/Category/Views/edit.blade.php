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
                    @if($propValue['type'] != 'password')
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

                        
{{-- @if($data->designElems['mainData']['url'] == 'users')
<div class="row">
    <div class="col-xs-12">
        <div class="form">
            <div class="row">
                <div class="col-xs-12">
                    <h4 class="title"> {{ trans('main.extraPermissions') }}</h4>
                </div>
            </div>
            <div class="formPayment">
                <div class="row">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="sortable-list tasklist list-unstyled">
                                <div class="row">
                                    @foreach($data->permissions as $key => $permission)
                                    <div class="col-xs-12 border-0 mb-3">
                                        <div class="card permission">
                                            <div class="card-header">
                                                @php 
                                                $allPerm = (array) $permission;
                                                @endphp
                                                <label class="ckbox prem">
                                                    <input type="checkbox" name="allPermission" {{ in_array($allPerm[array_keys($allPerm)[0]]['perm_name'], $data->data->extra_rules) ? 'checked' : '' }}>
                                                    <span class="tx-bold">{{ trans('main.'.lcfirst(str_replace('Controllers','',$key))) }} </span>
                                                </label>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    @php $i=0; @endphp
                                                    @foreach($permission as $one => $onePerm)
                                                    @if($i != 0 && $i % 6 == 0 )
                                                        </div><div class="row">
                                                    @endif   
                                                    <div class="col-md-2 mb-2">
                                                        <label class="ckbox prem">
                                                            <input type="checkbox" name="permission{{ $onePerm['perm_name'] }}" {{ in_array($one, $data->data->extra_rules) ? 'checked' : '' }}>
                                                            <span> {{ $onePerm['perm_title'] }}</span>
                                                        </label>
                                                    </div>
                                                    @php $i++ @endphp
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>
@endif --}}

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
