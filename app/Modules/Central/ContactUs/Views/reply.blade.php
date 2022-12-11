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
                            <h4 class="header-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{ trans('main.reply') }}</h4>
                        </div>
                    </div>
                    <hr>
                    <form class="form-horizontal" method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/postReply/'.$data->data->id) }}">
                        @csrf
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.clientMessage') }} :</label>
                            <div class="col-9">
                                <textarea class="form-control" name="message" placeholder="{{ trans('main.message') }}" disabled>{{ $data->data->message }}</textarea>
                                <input type="hidden" name="status" value="{{ $data->data->status }}">
                            </div>
                        </div>
                        <div class="form-group row mb-3">
                            <label for="inputPassword3" class="col-3 col-form-label">{{ trans('main.reply') }} :</label>
                            <div class="col-9">
                                <textarea class="form-control" name="reply" placeholder="{{ trans('main.reply') }}"></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-0 justify-content-end row">
                            <div class="col-9">
                                <button name="Submit" type="submit" class="btn btn-success AddBTN" id="SubmitBTN">{{ trans('main.reply') }}</button>
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

@section('modals')
@include('central.Partials.photoswipe_modal')
@endsection


{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('assets/dashboard/assets/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/assets/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('assets/dashboard/assets/components/myPhotoSwipe.js') }}"></script>      
@endsection


