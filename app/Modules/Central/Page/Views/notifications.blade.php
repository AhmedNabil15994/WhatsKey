{{-- Extends layout --}}
@extends('central.Layouts.Dashboard.master')
@section('title',$data->designElems['mainData']['title'])

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
                    <form class="form-horizontal" method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/create') }}">
                        @csrf
                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.titleAr') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ isset($data->data->title_ar) ? $data->data->title_ar : old('title_ar') }}" name="title_ar" id="inputEmail3" placeholder="{{ trans('main.titleAr') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.titleEn') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ isset($data->data->title_en) ? $data->data->title_en : old('title_en') }}" name="title_en" id="inputPassword3" placeholder="{{ trans('main.titleEn') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.descriptionAr') }} :</label>
                            <div class="col-9">
                                <textarea class="form-control" name="description_ar" placeholder="{{ trans('main.descriptionAr') }}">{{ isset($data->data->description_ar) ? $data->data->description_ar : old('description_ar') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.descriptionEn') }} :</label>
                            <div class="col-9">
                                <textarea class="form-control" name="description_en" placeholder="{{ trans('main.descriptionEn') }}">{{ isset($data->data->description_en) ? $data->data->description_en : old('description_en') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.whatsAppMessage') }} :</label>
                            <div class="col-9">
                                <div class="checkbox checkbox-success">
                                    <input id="whatsappMsg" class="whatsappMsg" type="checkbox" name="whatsAppMessage">
                                    <label for="whatsappMsg"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-9">
                                <button name="Submit" type="submit" class="btn btn-success AddBTN" id="SubmitBTN">{{ trans('main.add') }}</button>
                                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" type="reset" class="btn btn-danger Reset">{{ trans('main.back') }}</a>
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