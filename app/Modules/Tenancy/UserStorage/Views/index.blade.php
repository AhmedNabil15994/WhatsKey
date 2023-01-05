{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="screen">
   
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
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
<div class="row mb-5">
    @php 
        $result = round( ((int) $data->totalStorage > 0 ? (int) $data->totalSize / (int) $data->totalStorage : 0) ,2);
        $leftSpace = round( ((int) $data->totalStorage > 0 ?  (int) $data->totalStorage - (int) $data->totalSize  : 0)   ,2)
    @endphp
    <div class="col-xl-4">
        <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url({{asset('assets/tenant/media/svg/shapes/abstract-3.svg')}})">
            <div class="card-body my-4">
                <a href="#" class="card-title font-weight-bolder text-primary font-size-h6 mb-4 text-hover-state-dark d-block">{{ trans('main.storages') }}</a>
                <div class="font-weight-bold text-muted font-size-sm">
                <span class="text-dark-75 font-weight-bolder font-size-h2 mr-2">{{ round($data->totalStorage / 1024 ,2) }} </span>{{ trans('main.gigaB') }}</div>
                <div class="progress progress-xs mt-7 bg-primary-o-60">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url({{asset('assets/tenant/media/svg/shapes/abstract-3.svg')}})">
            <div class="card-body my-4">
                <a href="#" class="card-title font-weight-bolder text-success font-size-h6 mb-4 text-hover-state-dark d-block">{{ trans('main.used') }}</a>
                <div class="font-weight-bold text-muted font-size-sm">
                <span class="text-dark-75 font-weight-bolder font-size-h2 mr-2">{{ round($data->totalSize ,2) }} </span>{{ trans('main.migaB') }}</div>
                <div class="progress progress-xs mt-7 bg-success-o-60">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{$result}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url({{asset('assets/tenant/media/svg/shapes/abstract-3.svg')}})">
            <div class="card-body my-4">
                <a href="#" class="card-title font-weight-bolder text-danger font-size-h6 mb-4 text-hover-state-dark d-block">{{ trans('main.leftSpace') }}</a>
                <div class="font-weight-bold text-muted font-size-sm">
                <span class="text-dark-75 font-weight-bolder font-size-h2 mr-2">{{ round($leftSpace / 1024 ,2) }} </span>{{ trans('main.gigaB') }}</div>
                <div class="progress progress-xs mt-7 bg-danger-o-60">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{100 - $result}}%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex flex-row">
    <div class="flex-row-auto offcanvas-mobile w-200px w-xxl-275px" id="kt_todo_aside">
        <div class="card card-custom card-stretch">
            <div class="card-body px-5">
                <div class="navi navi-hover navi-active navi-link-rounded navi-bold navi-icon-center navi-light-icon">
                    <div class="navi-section mt-7 mb-2 font-size-h6 font-weight-bold pb-0">{{trans('main.folders')}}</div>

                    <div class="navi-item my-2">
                        <a href="{{ URL::to('/storage') }}" class="navi-link {{ Active(URL::to('/storage')) }} {{ Active(URL::to('/storage/users*')) }}">
                            <span class="navi-icon mr-4">
                                <span class="fa-icon fa-icon-lg">
                                   <i class="la la-users icon-xl"></i>
                                </span>
                            </span>
                            <span class="navi-text font-weight-bolder font-size-lg">{{ trans('main.users') }} </span>
                        </a>
                    </div>

                    @if(\Helper::checkRules('list-livechat'))
                    <div class="navi-item my-2">
                        <a href="{{ URL::to('/storage/chats') }}" class="navi-link {{ Active(URL::to('/storage/chats*')) }}">
                            <span class="navi-icon mr-4">
                                <span class="svg-icon svg-icon svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"></rect>
                                            <polygon fill="#000000" opacity="0.3" points="5 15 3 21.5 9.5 19.5"></polygon>
                                            <path d="M13.5,21 C8.25329488,21 4,16.7467051 4,11.5 C4,6.25329488 8.25329488,2 13.5,2 C18.7467051,2 23,6.25329488 23,11.5 C23,16.7467051 18.7467051,21 13.5,21 Z M8.5,13 C9.32842712,13 10,12.3284271 10,11.5 C10,10.6715729 9.32842712,10 8.5,10 C7.67157288,10 7,10.6715729 7,11.5 C7,12.3284271 7.67157288,13 8.5,13 Z M13.5,13 C14.3284271,13 15,12.3284271 15,11.5 C15,10.6715729 14.3284271,10 13.5,10 C12.6715729,10 12,10.6715729 12,11.5 C12,12.3284271 12.6715729,13 13.5,13 Z M18.5,13 C19.3284271,13 20,12.3284271 20,11.5 C20,10.6715729 19.3284271,10 18.5,10 C17.6715729,10 17,10.6715729 17,11.5 C17,12.3284271 17.6715729,13 18.5,13 Z" fill="#000000"></path>
                                        </g>
                                    </svg>
                                </span>
                            </span>
                            <span class="navi-text font-weight-bolder font-size-lg">{{ trans('main.livechat') }} </span>
                        </a>
                    </div>
                    @endif

                    @if(\Helper::checkRules('list-bots'))
                    <div class="navi-item my-2">
                        <a href="{{ URL::to('/storage/bots') }}" class="navi-link {{ Active(URL::to('/storage/bots*')) }}">
                            <span class="navi-icon mr-4">
                                <span class="fa-icon fa-icon-lg">
                                   <i class="fas fa-robot icon-lg"></i>
                                </span>
                            </span>
                            <span class="navi-text font-weight-bolder font-size-lg">{{ trans('main.bot') }} </span>
                        </a>
                    </div>
                    @endif

                    @if(\Helper::checkRules('list-group-messages'))
                    <div class="navi-item my-2">
                        <a href="{{ URL::to('/storage/groupMessages') }}" class="navi-link {{ Active(URL::to('/storage/groupMessages*')) }}">
                            <span class="navi-icon mr-4">
                                <span class="fa-icon fa-icon-lg">
                                   <i class="la la-envelope icon-xl"></i>
                                </span>
                            </span>
                            <span class="navi-text font-weight-bolder font-size-lg">{{ trans('main.groupMsgs') }} </span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="flex-row-fluid d-flex flex-column ml-lg-8">
        <div class="d-flex flex-column flex-grow-1">
            @if($data->parent == 'main')
            <div class="card card-custom formNumbers">
                <div class="card-header">
                    <h3 class="card-title">{{trans('main.files')}}</h3>
                </div>
                <div class="card-body">
                    <table class="datatable datatable-bordered datatable-head-custom" id="kt_datatable">
                        <thead>
                            <tr>
                                <th class="border-0">{{ trans('main.name') }}</th>
                                <th class="border-0">{{ trans('main.date') }}</th>
                                <th class="border-0">{{ trans('main.size') }}</th>
                                <th class="border-0" style="width: 80px;">{{ trans('main.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->data as $item)
                            <tr>
                                <td>
                                    <a href="{{ URL::current().'/'.($data->type == 'users' ? $data->type.'/' : '').$item->id }}" class="text-reset">{{ $item->id }}</a>
                                    <i class="iconFile flaticon-folder"></i>
                                </td>
                                <td>{{ $item->created_at != null ? date('D m, Y',strtotime($item->created_at)) : '-----' }}</td>
                                <td>{{ $item->folder_size . ' ' . trans('main.migaB') }}</td>
                                <td>
                                    <a class="btn btn-outline-success btn-md btn-icon" data-toggle="tooltip" data-original-title="{{trans('main.view')}}" href="{{ URL::current().(Request::segment(2) != 'users' && Request::segment(2) == null ?  '/'.$data->type : '').'/'.$item->id }}"><i class="la la-eye icon-lg"></i></a>
                                    
                                    @if(\Helper::checkRules('delete-storage'))
                                    <a class="btn btn-outline-danger btn-md btn-icon" data-toggle="tooltip" data-original-title="{{trans('main.delete')}}" href="#" onclick="deleteStorageFile('{{URL::current().(Request::segment(2) != 'users' && Request::segment(2) == null ?  '/'.$data->type : '').'/'.$item->id.'/remove'}}')"><i class="la la-trash icon-lg"></i></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="card-header pb-0 mb-5">
                <h3 class="card-title">{{trans('main.files')}}</h3>
            </div>
            <div class="row">
                @foreach($data->data as $item)
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
                    <div class="card card-custom gutter-b card-stretch">
                        <div class="card-header border-0">
                            <h3 class="card-title"></h3>
                            <div class="card-toolbar">
                                <div class="dropdown dropdown-inline">
                                    <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-xs dropdown-menu-right">
                                        <ul class="navi navi-hover">
                                            <li class="navi-item">
                                                <a href="{{$item->file}}" target="_blank" class="navi-link">
                                                    <span class="navi-icon">
                                                        <i class="flaticon2-drop"></i>
                                                    </span>
                                                    <span class="navi-text">{{ trans('main.download') }}</span>
                                                </a>
                                            </li>
                                            @if(\Helper::checkRules('delete-storage'))
                                            @php 
                                            $deleteUrl = URL::current().'/'.$item->name.'/removeFile';
                                            if($data->type != 'chats'){
                                                $deleteUrl =  URL::current().'/remove?fileName='.$item->name;
                                            }
                                            @endphp
                                            <li class="navi-item">
                                                <a href="#" class="navi-link"  onclick="deleteStorageFile('{{ $deleteUrl }}')">
                                                    <span class="navi-icon">
                                                        <i class="flaticon2-list-3"></i>
                                                    </span>
                                                    <span class="navi-text">{{ trans('main.delete') }}</span>
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img alt="" class="h-65px" src="{{asset('assets/tenant/media/svg/files/'.$item->extension.'.svg')}}">
                                <a href="{{$item->file}}" class="text-dark-75 font-weight-bold mt-15 font-size-lg" target="_blank">{{$item->name}}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script src="{{asset('assets/tenant/js/pages/crud/ktdatatable/base/html-table.js')}}"></script>
@endsection
