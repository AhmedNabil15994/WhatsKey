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
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.name_ar') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ old('title_ar') }}" name="title_ar" id="inputEmail3" placeholder="{{ trans('main.name_ar') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.name_en') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ old('title_en') }}" name="title_en" id="inputPassword3" placeholder="{{ trans('main.name_en') }}">
                                <input type="hidden" name="status">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.monthly_price') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ old('monthly_price') }}" name="monthly_price" id="inputPassword3" placeholder="{{ trans('main.monthly_price') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.annual_price') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ old('annual_price') }}" name="annual_price" id="inputPassword3" placeholder="{{ trans('main.annual_price') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.monthly_after_vat') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ old('monthly_after_vat') }}" name="monthly_after_vat" id="inputPassword3" placeholder="{{ trans('main.monthly_after_vat') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.annual_after_vat') }} :</label>
                            <div class="col-9">
                                <input type="text" class="form-control" value="{{ old('annual_after_vat') }}" name="annual_after_vat" id="inputPassword3" placeholder="{{ trans('main.annual_after_vat') }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword5" class="col-3 col-form-label">{{ trans('main.features') }} :</label>
                            <div class="col-9">
                                <div class="sortable-list tasklist list-unstyled">
                                    <div class="row">
                                        @foreach($data->features as $key => $feature)
                                        <div class="col-xs-12 col-md-6 border-0">
                                            <div class="checkbox checkbox-blue checkbox-single float-left">
                                                <input type="checkbox" name="features[]" value="{{ $feature->id }}">
                                                <label></label>
                                            </div>
                                            <p>{{ $feature->title }}</p>
                                            <div class="clearfix"></div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-6">
                                            <button type="button" class="btn btn-info SelectAllCheckBox ml-2 mr-2">{{ trans('main.selectAll') }}</button>
                                            <button type="button" class="btn btn-danger UnSelectAllCheckBox">{{ trans('main.deselectAll') }}</button>
                                        </div>            
                                    </div>
                                    <hr>
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