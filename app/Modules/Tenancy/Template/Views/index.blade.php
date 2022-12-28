{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style>
    table tbody tr .phone, table tbody tr .date{
        width: unset;
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
            'title' => $data->designElems['mainData']['title'],
            'url' => $data->designElems['mainData']['url']
        ],
    ]
])
@endsection

@section('content')

<input type="hidden" name="designElems" value="{{ json_encode($data->designElems) }}">

@if(!isset($data->dis) || $data->dis != true)
<input type="hidden" name="data-area" value="{{ \Helper::checkRules('edit-'.$data->designElems['mainData']['nameOne']) }}">
<input type="hidden" name="data-cols" value="{{ \Helper::checkRules('delete-'.$data->designElems['mainData']['nameOne']) }}">
@endif

@if($data->designElems['mainData']['url'] == 'bots' || $data->designElems['mainData']['url'] == 'botPlus')
<input type="hidden" name="data-tabs" value="{{ \Helper::checkRules('copy-'.$data->designElems['mainData']['nameOne']) }}">
@endif

@if($data->designElems['mainData']['url'] == 'tickets')
<input type="hidden" name="tenant" value="1">
@endif

@if($data->designElems['mainData']['url'] == 'groupMsgs' || $data->designElems['mainData']['url'] == 'tickets' || $data->designElems['mainData']['url'] == 'invoices' || $data->designElems['mainData']['name'] == 'orders' || $data->designElems['mainData']['name'] == 'products')
<input type="hidden" name="data-tab" value="{{ \Helper::checkRules('view-'.$data->designElems['mainData']['nameOne']) }}">
@endif

@if($data->designElems['mainData']['url'] == 'groupNumbers')
<input type="hidden" name="data-tests" value="{{ \Helper::checkRules('export-contacts') }}">
@endif
@php 
$varObj = \App\Models\Variable::getVar('ME');
@endphp

@if($data->designElems['mainData']['url'] == 'categories' && $varObj && !json_decode($varObj)->isBussines)
    <div class="alert alert-custom alert-primary" role="alert">
        <div class="alert-icon"><i class="flaticon-warning"></i></div>
        <div class="alert-text">{{trans('main.labelsConfigure')}}</div>
        <div class="alert-close float-right">
            @if(\Helper::checkRules('sync-categories'))
            <a class="btn btn-light-primary " href="{{ URL::to('/categories/syncLabels') }}">{{trans('main.syncLabels')}}</a>
            @endif
        </div>
        <div class="clearfix"></div>
    </div>
@endif

<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="{{ $data->designElems['mainData']['icon'] }} text-primary"></i>
            </span>
            <h3 class="card-label">{{ $data->designElems['mainData']['title'] }}</h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline mr-2">
                <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>{{trans('main.export')}}
                </button>
                <!--begin::Dropdown Menu-->
                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                    <!--begin::Navigation-->
                    <ul class="navi flex-column navi-hover py-2">
                        <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">{{trans('main.choose')}}</li>
                        <li class="navi-item">
                            <a href="#" class="navi-link navi-print">
                                <span class="navi-icon">
                                    <i class="la la-print"></i>
                                </span>
                                <span class="navi-text">Print</span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link navi-copy">
                                <span class="navi-icon">
                                    <i class="la la-copy"></i>
                                </span>
                                <span class="navi-text">Copy</span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link navi-excel">
                                <span class="navi-icon">
                                    <i class="la la-file-excel-o"></i>
                                </span>
                                <span class="navi-text">Excel</span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link navi-csv">
                                <span class="navi-icon">
                                    <i class="la la-file-text-o"></i>
                                </span>
                                <span class="navi-text">CSV</span>
                            </a>
                        </li>
                        <li class="navi-item">
                            <a href="#" class="navi-link navi-pdf">
                                <span class="navi-icon">
                                    <i class="la la-file-pdf-o"></i>
                                </span>
                                <span class="navi-text">PDF</span>
                            </a>
                        </li>
                    </ul>
                    <!--end::Navigation-->
                </div>
                <!--end::Dropdown Menu-->
            </div>
            <!--end::Dropdown-->
            @if(!isset($data->dis) || $data->dis != true)

            @if(\Helper::checkRules('add-'.$data->designElems['mainData']['nameOne']) && (!isset($data->disAdd) || $data->disAdd != true))
            <!--begin::Button-->
            <a class="btn btn-light-success font-weight-bolder mr-2" href="{{ URL::to('/'.$data->designElems['mainData']['url'].'/add') }}">
                <span class="svg-icon svg-icon-md">
                    @if($data->designElems['mainData']['url'] == 'groupMsgs')
                    <i class="si si-cursor"></i> 
                    @else
                    <i class="flaticon-add"></i> 
                    @endif
                </span>
                {{ $data->designElems['mainData']['addOne'] }}  
            </a>
            <!--end::Button-->
            @endif

            @if(\Helper::checkRules('edit-'.$data->designElems['mainData']['nameOne']) && $data->designElems['mainData']['url'] != 'groupMsgs'  && (!isset($data->disFastEdit) || $data->disFastEdit != true))
                <a href="#" class="edit quickEdit btn btn-icon btn-light-warning btn-md mr-2" data-toggle="tooltip" data-original-title="{{ trans('main.fastEdit') }}"><i class="flaticon2-edit"></i></a>
            @endif

            <a href="#" class="btn iconAdditions btn-icon search-mode btn-light-info btn-md mr-2" data-toggle="tooltip" data-original-title="{{ trans('main.advancedSearchTip') }}"><i class="flaticon2-rocket-1"></i></a>
            @if($data->designElems['mainData']['url'] == 'bots' && IS_ADMIN && \Helper::checkRules('add-'.$data->designElems['mainData']['nameOne']))
                <a href="#" data-toggle="modal" data-target="#unknownBot" class="btn unknownReply btn-icon btn-light-dark btn-md mr-2" data-toggle="tooltip" data-original-title="{{ trans('main.unknownReply') }}"><i class="flaticon2-chat-1"></i></a>
            @endif
            @endif
        </div>
    </div>
    <div class="card-body">
        @if(!empty($data->designElems['searchData']))
        <!--begin: Search Form-->
        <form class="mb-5 searchForm">
            <div class="row mb-6">
                @foreach($data->designElems['searchData'] as $searchKey => $searchItem)
                <div class="col-lg-3 mb-lg-4 mb-6">
                    <label>{{ $searchItem['label'] }}:</label>
                    @if(in_array($searchItem['type'],['email','text','number','password']))
                        @if($searchKey == 'from' || $searchKey == 'to')
                            <input type="{{ $searchItem['type'] }}" data-date-format="dd-mm-yyyy" data-date-autoclose="true" class="{{ $searchItem['class'] }}" value="{{ Request::get($searchKey) }}" name="{{ $searchKey }}" id="{{ $searchItem['id'] }}" placeholder="{{ $searchItem['label'] }}">
                        @else
                            <input type="{{ $searchItem['type'] }}" data-col-index="{{$searchItem['index']}}" class="{{ $searchItem['class'] }}" value="{{ Request::get($searchKey) }}" placeholder="{{ $searchItem['label'] }}" name="{{ $searchKey }}">
                        @endif
                    @endif
                    @if($searchItem['type'] == 'select')
                        <select class="form-control" data-toggle="select2" name="{{ $searchKey }}">
                            <option value=" ">{{ trans('main.choose') }}</option>
                            @foreach($searchItem['options'] as $group)
                            @php $group = (object) $group; @endphp
                            <option value="{{ $group->id }}">{{ $group->title }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                @endforeach
            </div>
            
            <div class="row mt-8">
                <div class="col-lg-12 text-right">
                    <button class="btn btn-primary btn-primary--icon" id="kt_search">
                        <span>
                            <i class="la la-search"></i>
                            <span>{{trans('main.search')}}</span>
                        </span>
                    </button>&#160;&#160;
                    <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                        <span>
                            <i class="la la-close"></i>
                            <span>{{trans('main.cancel')}}</span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
        <!--end: Search Form-->
        @endif
        <!--begin: Datatable-->
        <table class="table table-bordered table-hover table-checkable" id="kt_datatable" style="margin-top: 13px !important">
            <thead>
                <tr>
                    @foreach($data->designElems['tableData'] as $one)
                    <th>{{ $one['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
        </table>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->

@endsection

@section('modals')
@if($data->designElems['mainData']['url'] == 'bots' && IS_ADMIN)
@include('tenant.Partials.unknownBot')
@endif
@include('tenant.Partials.search_modal')
@endsection

{{-- Scripts Section --}}

@section('scripts')
<script src="{{ asset('assets/tenant/components/globals.js')}}"></script>           
<script src="{{ asset('assets/tenant/components/datatables.js')}}"></script>           
@endsection
