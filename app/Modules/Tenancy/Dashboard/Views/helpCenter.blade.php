{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',trans('main.helpCenter'))
@section('pageName',trans('main.helpCenter'))

@section('styles')
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
            'title' => trans('main.helpCenter'),
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
<div class="row mb-2">
    <div class="col-lg-6">
        <div class="card card-custom mb-2 bg-diagonal bg-diagonal-light-primary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between p-4">
                    <div class="d-flex flex-column mr-5">
                        <a href="#" class="h4 text-dark text-hover-primary mb-5">{{trans('main.supportInfo')}}</a>
                        <p class="text-dark-50"><i class="la la-phone"></i>{{ $data->phone }}</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <a href="{{URL::to('/tickets')}}" class="btn font-weight-bolder text-uppercase font-size-lg btn-primary py-3 px-6">{{trans('main.tickets')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-custom mb-2 bg-diagonal bg-diagonal-light-success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between p-4">
                    <div class="d-flex flex-column mr-5">
                        <a href="#" class="h4 text-dark text-hover-primary mb-5">{{ trans('main.pinCode') }}</a>
                        <p class="text-dark-50"><i class="la la-key"></i>{{ $data->pin_code }}</p>
                    </div>
                    <div class="ml-6 flex-shrink-0">
                        <a href="#" data-toggle="modal" data-target="#kt_chat_modal" class="btn font-weight-bolder text-uppercase font-size-lg btn-success py-3 px-6">{{trans('main.contactUs')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card card-custom formNumbers">
    <div class="card-header">
        <h3 class="card-title">
            <span class="svg-icon svg-icon-primary svg-icon-2x">
               <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path d="M12.8434797,16 L11.1565203,16 L10.9852159,16.6393167 C10.3352654,19.064965 7.84199997,20.5044524 5.41635172,19.8545019 C2.99070348,19.2045514 1.55121603,16.711286 2.20116652,14.2856378 L3.92086709,7.86762789 C4.57081758,5.44197964 7.06408298,4.00249219 9.48973122,4.65244268 C10.5421727,4.93444352 11.4089671,5.56345262 12,6.38338695 C12.5910329,5.56345262 13.4578273,4.93444352 14.5102688,4.65244268 C16.935917,4.00249219 19.4291824,5.44197964 20.0791329,7.86762789 L21.7988335,14.2856378 C22.448784,16.711286 21.0092965,19.2045514 18.5836483,19.8545019 C16.158,20.5044524 13.6647346,19.064965 13.0147841,16.6393167 L12.8434797,16 Z M17.4563502,18.1051865 C18.9630797,18.1051865 20.1845253,16.8377967 20.1845253,15.2743923 C20.1845253,13.7109878 18.9630797,12.4435981 17.4563502,12.4435981 C15.9496207,12.4435981 14.7281751,13.7109878 14.7281751,15.2743923 C14.7281751,16.8377967 15.9496207,18.1051865 17.4563502,18.1051865 Z M6.54364977,18.1051865 C8.05037928,18.1051865 9.27182488,16.8377967 9.27182488,15.2743923 C9.27182488,13.7109878 8.05037928,12.4435981 6.54364977,12.4435981 C5.03692026,12.4435981 3.81547465,13.7109878 3.81547465,15.2743923 C3.81547465,16.8377967 5.03692026,18.1051865 6.54364977,18.1051865 Z" fill="#000000"></path>
                    </g>
                </svg>
            </span>
            {{trans('main.helpCenter')}}
        </h3>
    </div>
    <div class="card-body">
        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ URL::to('/tickets/create') }}">
            @csrf
            <div class="form-group">
                <label>{{ trans('main.client') }}:</label>
                <select class="form-control" data-toggle="select2" name="user_id">
                    <option value="">{{ trans('main.choose') }}</option>
                    @foreach($data->clients as $client)
                    <option value="{{ $client->id }}" {{ $client->id == old('user_id') || $client->id == ROOT_ID ? 'selected' : '' }}>{{ '#'.$client->id .' - '. $client->name }}</option>
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
            <div class="form-group textWrap">
                <label>{{ trans('main.subject') }}:</label>
                <input class="form-control" type="text" value="{{ old('subject') }}" name="subject" id="inputEmail3" placeholder="{{ trans('main.subject') }}" />
                <input type="hidden" name="status" value="1">
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            <div class="form-group textWrap">
                <label>{{ trans('main.messageContent') }}:</label>
                <textarea class="form-control" name="description" placeholder="{{ trans('main.messageContent') }}">{{ old('description') }}</textarea>
                <i class="la la-smile icon-xl emoji-icon"></i>
                <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            </div>
            @if(\Helper::checkRules('uploadImage-ticket'))
            <div class="form-group">
                <label>{{ trans('main.files') }}:</label>
                <div class="dropzone dropzone-default" id="kt_dropzone_111">
                    <div class="dropzone-msg dz-message needsclick">
                        <i class="flaticon-upload"></i>
                        <h3 class="dropzone-msg-title">{{ trans('main.attachFiles') }}</h3>
                    </div>
                </div>
            </div>
            @endif
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.add')}}</button>
                <a href="{{ URL::to('/dashboard') }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </form>
    </div>
</div>
@endsection


@section('scripts')
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection
