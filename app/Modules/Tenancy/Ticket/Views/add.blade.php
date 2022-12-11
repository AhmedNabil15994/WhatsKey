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
                <label>{{ trans('main.client') }}:</label>
                <select class="form-control" data-toggle="select2" name="user_id">
                    <option value="">{{ trans('main.choose') }}</option>
                    @foreach($data->clients as $client)
                    <option value="{{ $client->id }}" {{ $client->id == old('user_id') || $client->id == USER_ID ? 'selected' : '' }}>{{ '#'.$client->id .' - '. $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>{{ trans('main.department') }}:</label>
                <select class="form-control" data-toggle="select2" name="department_id">
                    <option value="">{{ trans('main.choose') }}</option>
                    @foreach($data->departments as $department)
                    <option value="{{ $department->id }}" {{ $department->id == old('department_id') ? 'selected' : '' }}>{{ $department->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>{{ trans('main.subject') }}:</label>
                <input class="form-control" type="text" value="{{ old('subject') }}" name="subject" id="inputEmail3" placeholder="{{ trans('main.subject') }}" />
            </div>
            <div class="form-group">
                <label>{{ trans('main.messageContent') }}:</label>
                <textarea class="form-control" name="description" placeholder="{{ trans('main.messageContent') }}">{{ old('description') }}</textarea>
            </div>
            @if(\Helper::checkRules('uploadImage-ticket'))
            <div class="form-group">
                <label>{{ trans('main.files') }}:</label>
                <div class="dropzone dropzone-default" id="kt_dropzone_1">
                    <div class="dropzone-msg dz-message needsclick">
                        <i class="flaticon-upload"></i>
                        <h3 class="dropzone-msg-title">{{ trans('main.attachFiles') }}</h3>
                    </div>
                </div>
            </div>
            @endif
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.add')}}</button>
                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>

 
@endsection

@section('scripts')
<script src="{{asset('assets/tenant/js/pages/crud/forms/widgets/select2.js')}}"></script>
@endsection
