{{-- Extends layout --}}
@extends('central.Layouts.Dashboard.master')
@section('title',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css">
    body{
        overflow-x: hidden;
    }
</style>
@endsection

{{-- Content --}}

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="header-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{ $data->designElems['mainData']['title'] }}</h4>
                        </div>
                    </div>
                    <hr>
                    <form class="form-horizontal" method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/update/'.$data->data->id) }}">
                        @csrf
                        <input type="hidden" name="status">
                        @foreach($data->designElems['modelData'] as $propKey => $propValue)
                        @if(in_array($propValue['type'], ['email','text','number','password','tel']))
                        <div class="form-group row mb-2">
                            <label for="" class="col-3 col-form-label">{{ $propValue['label'] }} :</label>
                            <div class="col-9">
                                <input class="{{ $propValue['class'] }}" {{ $propValue['specialAttr'] }} type="{{ $propValue['type'] }}" name="{{ $propKey }}" value="{{ $propValue['type'] != 'password' ? $data->data->$propKey : '' }}" placeholder="{{ $propValue['label'] }}">
                                <span class="m-form__help LastUpdate float-right mt-1 mb-0">{{ trans('main.created_at') }} :  {{ $data->data->created_at }}</span>
                            </div>
                        </div>
                        @endif

                        @if($propValue['type'] == 'textarea')
                        <div class="form-group row mb-2">
                            <label for="" class="col-3 col-form-label">{{ $propValue['label'] }} :</label>
                            <div class="col-9">
                                <textarea {{ $propValue['specialAttr'] }} name="{{ $propKey }}" class="{{ $propValue['class'] }}" placeholder="{{ $propValue['label'] }}">{{ old($propKey) }}</textarea>
                                <span class="m-form__help LastUpdate float-right mt-1 mb-0">{{ trans('main.created_at') }} :  {{ $data->data->created_at }}</span>
                            </div>
                        </div>
                        @endif

                        @if($propValue['type'] == 'select')
                            @if($data->designElems['mainData']['url'] == 'users' && $propKey != 'channels')
                            <div class="form-group row mb-2">
                                <label class="col-3 col-form-label">{{ $propValue['label'] }} :</label>
                                <div class="col-9">
                                    <select class="form-control" name="{{ $propKey }}">
                                        <option value="">{{ trans('main.choose') }}</option>
                                        @foreach($propValue['options'] as $group)
                                        @php $group = (object) $group; @endphp
                                        <option value="{{ $group->id }}" {{ $data->data->$propKey == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                    <span class="m-form__help LastUpdate float-right mt-1 mb-0">{{ trans('main.created_at') }} :  {{ $data->data->created_at }}</span>
                                </div>
                            </div> 
                            @elseif($data->designElems['mainData']['url'] == 'users' && $propKey == 'channels')
                            <div class="form-group row mb-2">
                                <label class="col-3 col-form-label">{{ $propValue['label'] }} :</label>
                                <div class="col-9">
                                    <select class="form-control" name="{{ $propKey }}">
                                        <option value="">{{ trans('main.choose') }}</option>
                                        @foreach($propValue['options'] as $group)
                                        @php $group = (object) $group; @endphp
                                        <option value="{{ $group->id }}" {{ in_array($group->id,$data->data->channelIDS) ? 'selected' : '' }}>{{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                    <span class="m-form__help LastUpdate float-right mt-1 mb-0">{{ trans('main.created_at') }} :  {{ $data->data->created_at }}</span>
                                </div>
                            </div>
                            @else 
                            <div class="form-group row mb-2">
                                <label class="col-3 col-form-label">{{ $propValue['label'] }} :</label>
                                <div class="col-9">
                                    <select class="selectpicker" data-style="btn-outline-myPR" name="{{ $propKey }}">
                                        <option value="">{{ trans('main.choose') }}</option>
                                        @foreach($propValue['options'] as $group)
                                        @php $group = (object) $group; @endphp
                                        <option value="{{ $group->id }}" {{ $data->data->$propKey == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                                        @endforeach
                                    </select>
                                    <span class="m-form__help LastUpdate float-right mt-1 mb-0">{{ trans('main.created_at') }} :  {{ $data->data->created_at }}</span>
                                </div>
                            </div> 
                            @endif
                        @endif
                        @endforeach

                        @if($data->designElems['mainData']['url'] == 'users')
                        <div class="form-group row mb-2">
                            <label for="inputPassword5" class="col-3 col-form-label">{{ trans('main.extraPermissions') }} :</label>
                            <div class="col-9">
                                <div class="sortable-list tasklist list-unstyled">
                                    <div class="row">
                                        @php $i =0; @endphp
                                        @foreach($data->permissions as $key => $permission)
                                            @if($i % 3 == 0)
                                            </div><div class="row">
                                            @endif
                                        <div class="col-xs-12 col-md-4 border-0 mb-3">
                                            <li>
                                                @foreach($permission as $one => $onePerm)
                                                <div class="checkbox checkbox-blue checkbox-single float-left">
                                                    <input type="checkbox" {{ in_array($one, $data->data->extra_rules) ? 'checked' : '' }} name="permission{{ $onePerm['perm_name'] }}">
                                                    <label></label>
                                                </div>
                                                <p>{{ $onePerm['perm_title'] }}</p>
                                                <div class="clearfix"></div>
                                                @if(count($permission) > 1)
                                                <hr>
                                                @endif
                                                @endforeach
                                            </li>
                                        </div>
                                        @php $i++; @endphp
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($propValue['type'] == 'image' && \Helper::checkRules('uploadImage-'.$data->designElems['mainData']['nameOne']))
                        <div class="form-group row mb-2">
                            <label class="col-3 col-form-label">{{ $propValue['label'] }} :</label>
                            <div class="col-9">
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
                            </div>
                        </div>
                        @endif

                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-9">
                                <button name="Submit" type="submit" class="btn btn-success AddBTN" id="SubmitBTN">{{ trans('main.edit') }}</button>
                                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" type="reset" class="btn btn-danger Reset">{{ trans('main.back') }}</a>
                                
                            </div>
                        </div>
                    </form>
                    <!--end: Datatable-->
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
    </div>     
</div>

@endsection

@section('modals')
@include('central.Partials.photoswipe_modal')
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('assets/dashboard/assets/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/assets/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/assets/components/myPhotoSwipe.js') }}"></script>      
@endsection
