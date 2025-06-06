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
                    <form class="form-horizontal" method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/update/'.$data->data->id) }}">
                        @csrf
                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.name_ar') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ $data->data->title_ar }}" name="title_ar" id="inputEmail3" placeholder="{{ trans('main.name_ar') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.name_en') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ $data->data->title_en }}" name="title_en" id="inputPassword3" placeholder="{{ trans('main.name_en') }}">
                                <input type="hidden" name="status" value="{{ $data->data->status }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.module') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ $data->data->module }}" name="module" placeholder="{{ trans('main.module') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.monthly_price') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ $data->data->monthly_price != 0 ? $data->data->monthly_price : 0 }}" name="monthly_price" id="inputPassword3" placeholder="{{ trans('main.monthly_price') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.annual_price') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ $data->data->annual_price != 0 ? $data->data->annual_price : 0 }}" name="annual_price" id="inputPassword3" placeholder="{{ trans('main.annual_price') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.monthly_after_vat') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ $data->data->monthly_after_vat != 0 ? $data->data->monthly_after_vat : 0 }}" name="monthly_after_vat" id="inputPassword3" placeholder="{{ trans('main.monthly_after_vat') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.annual_after_vat') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ $data->data->annual_after_vat != 0 ? $data->data->annual_after_vat : 0 }}" name="annual_after_vat" id="inputPassword3" placeholder="{{ trans('main.annual_after_vat') }}">
                            </div>
                        </div>
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
    <!-- end row-->
</div> <!-- container -->
@endsection