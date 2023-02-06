{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css">
    .checkbox-single{
        width: 50px;
    }
    html[dir="rtl"] .form:not(.main) input[type="checkbox"]{
        right: 20px;
    }
    .form p.data{
        display: inline-block;
        margin-bottom: 0;
        font-weight: bold;
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
{{-- Content --}}


@section('content')
@if($data->checkGroupMsg == 1)
<div class="alert alert-custom alert-dark" role="alert" >
    <div class="alert-icon"><i class="flaticon-info"></i></div>
    <div class="alert-text">{{trans('main.groupNumberNotify')}}</div>
</div>
@endif

<input type="hidden" name="modelProps" value="{{ json_encode($data->modelProps) }}">
<div class="card card-custom formNumbers">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <form class="form" method="post" action="{{ URL::to('/groupNumbers/addGroupNumbers/create') }}">
        @csrf
        <div class="card-body">
            <input type="hidden" name="status">
            <div class="form-group mb-4">
                <label>{{ trans('main.group') }}</label>
                <select class="form-control" data-toggle="select2" name="group_id">
                    <option value="">{{ trans('main.choose') }}</option>
                    @foreach($data->groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>{{ $group->title }}</option>
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

            <div class="form-group mb-3 mt-6">
                <label class="titleLabel hidden-lg hidden-md">{{ trans('main.attachExcel') }}</label>
                <div class="upload form-control">
                    <input type="file" name="file" accept=".xlsx,.csv">
                    <i class="flaticon-upload"></i>
                    اسحب الملفات هنا أو انقر هنا للرفع .
                </div>
                <div class="uploadFile">{{ trans('main.excelExample') }} (<a target="_blank" href="{{ asset('uploads/ImportGroupNumbers.xlsx') }}">{{ trans('main.download') }}</a> )</div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary mr-2">{{trans('main.add')}}</button>
                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" class="btn btn-secondary">{{trans('main.back')}}</a>
            </div>
        </div>
        <div class="col-md-12">
            <form class="form card-body">
                <h2 class="title">{{ trans('main.fileContent') }}</h2>
                <input type="hidden" name="files" value="">
                <div class="row">
                    <div class="sortable-list tasklist list-unstyled col">
                        <div class="" id="colData">
                            <p>{{ trans('main.noDataFound') }}</p>
                        </div>
                    </div>
                </div>            
            </form>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
<script src="{{ asset('assets/tenant/components/addNumberToGroup.js') }}" type="text/javascript"></script>
@endsection
