{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="screen">
    .form .btnsTabs li{
        width: 200px;
    }
    .form textarea{
        height: 250px;
    }
    .form p.data{
        display: inherit;
    }
    .col-xs-12.text-right.actions .nextPrev{
        padding: 10px 30px 30px 30px;
    }
    .form .content{
        padding-bottom: 0;
    }
    textarea[name="notes"]{
        margin-top: 25px !important;
    }
    li:not([titlehover="link4"]) i:not(.flaticon-left-arrow){
        margin-top: 15px;
    }
    .nextPrev{
        margin-left: 30px;
        margin-right: 30px;
    }
    .footer .top.active svg{
        margin-top: 10px;
    }
</style>
@endsection
@section('breadcrumbs')
@include('tenant.Layouts.breadcrumb',[
    'breadcrumbs' => [
        [
            'title' => trans('main.dashboard'),
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
    <form class="supportForm" method="POST" action="{{ URL::to('/contacts/update/'.$data->data->id) }}">
        @csrf
        <div class="card-body">
            <div class="form-group mb-4">
                <label>{{ trans('main.group') }}</label>
                <select class="form-control" data-toggle="select2" name="group_id">
                    <option value="">{{ trans('main.choose') }}</option>
                    @foreach($data->groups as $group)
                    <option value="{{ $group->id }}" {{ $data->data->group_id == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
                    @endforeach
                    <option value="@">{{ trans('main.add') }}</option>
                </select>
            </div>
            
            <div class="new hidden mb-4">
                <hr>
                <p style="padding: 30px 0"> {{ trans('main.add').' '.trans('main.group') }}</p>
                <div class="form-group mb-3">
                    <label for="inputEmail3">{{ trans('main.titleAr') }} :</label>
                    <input type="text" class="name_ar form-control" name="name_ar" placeholder="{{ trans('main.titleAr') }}">
                </div>
                <div class="form-group mb-3">
                    <label for="inputEmail4" class="titleLabel">{{ trans('main.titleEn') }} :</label>
                    <input type="text" class="name_en form-control" name="name_en" placeholder="{{ trans('main.titleEn') }}">
                </div>
                <button type="button" class="btn btn-success mb-2 addGR float-right">{{ trans('main.add').' '.trans('main.group') }}</button>
                <div class="clearfix"></div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label> {{ trans('main.name') }}</label>
                        <input class="form-control" value="{{$data->data->name}}" type="text" name="client_name" placeholder="{{ trans('main.name') }}">
                    </div>
                    <div class="form-group">
                        <label> {{ trans('main.email') }}</label>
                        <input class="form-control" value="{{$data->data->email}}" type="email" name="email" placeholder="{{ trans('main.email') }}">
                    </div>
                    <div class="form-group">
                        <label> {{ trans('main.phone') }}</label>
                        <input type="hidden" name="phone">
                        <input type="tel" id="telephone" value="{{$data->data->phone}}" class="form-control" placeholder="{{ trans('main.phone') }}">
                    </div>
                </div> <!-- end col -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label> {{ trans('main.country') }}</label>
                        <input class="form-control" type="text" value="{{$data->data->country}}" name="country" placeholder="{{ trans('main.country') }}">
                    </div>
                    <div class="form-group">
                        <label> {{ trans('main.city') }}</label>
                        <input class="form-control" type="text" value="{{$data->data->city}}" name="city" placeholder="{{ trans('main.city') }}">
                    </div>
                    <div class="form-group">
                        <label> {{ trans('main.lang') }}</label>
                        <select class="form-control" data-toggle="select2" name="lang">
                            <option value="">{{ trans('main.choose') }}</option>
                            <option value="0" {{$data->data->lang == 0 ? 'selected' : ''}}>{{ trans('main.arabic') }}</option>
                            <option value="1" {{$data->data->lang == 1 ? 'selected' : ''}}>{{ trans('main.english') }}</option>
                        </select>
                    </div>
                </div> <!-- end col -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label> {{ trans('main.extraInfo') }}</label>                   
                        <textarea class="form-control" name="notes" placeholder="{{ trans('main.extraInfo') }}">{{$data->data->notes}}</textarea>
                    </div>
                </div>
            </div> <!-- end row -->
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.edit')}}</button>
                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </div>
        
    </form>
</div>
@endsection

@section('scripts')
@endsection
