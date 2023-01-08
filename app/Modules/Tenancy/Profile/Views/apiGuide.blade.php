{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="screen">
    .form .btnsTabs{
        position: unset;
        margin-top: 25px;
        height: 54px;
    }
    .form .content{
        text-align: left;
        direction: ltr;
    }
    .tab{
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    code{
        overflow-wrap: break-word;
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
            'url' => \URL::current()
        ],
    ]
])
@endsection
@section('content')
<div class="card card-custom formNumbers mb-5">
    <div class="card-header">
        <h3 class="card-title">{{ trans('main.intro') }}</h3>
    </div>
    <div class="card-body">
        <h2 class="titleApi text-success text-50 font-weight-bold mb-5">{{ trans('main.instructions') }}</h2>
        <div class="desc">
            <p>-{{ trans('main.instructions_p1') }}</p>
            <p>-{{ trans('main.instructions_p2') }}</p>
            <p>-{{ trans('main.instructions_p3') }}</p>
            <p>-{{ trans('main.instructions_p4') }}</p>
        </div>
        <a href="{{ asset('assets/tenant/codes/phpClass/MainWhatsKey.zip') }}" class="btn btn-md btn-warning float-right"><i class="la la-download"></i> {{ trans('main.downloadLibrary') }}</a>
    </div>
</div>
<div class="card card-custom formNumbers">
    <div class="card-header">
        <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
    </div>
    <div class="card-body">
        @for ($i = 1; $i <= 40 ; $i++)
            <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample{{$i}}">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title {{$i != 1 ? 'collapsed' : ''}}" data-toggle="collapse" data-target="#collapseOne{{$i}}">
                            {{ trans('main.send_msg_'.$i) }}
                        </div>
                    </div>
                    <div id="collapseOne{{$i}}" class="collapse {{$i == 1 ? 'show' : ''}}" data-parent="#accordionExample{{$i}}">
                        <div class="card-body">
                            <div class="example-preview">
                                @if($i <= 20)
                                <p>{{ trans('main.send_msg_p1') }}</p>
                                @endif
                                <p>{{ trans('main.send_msg_p2') }}</p>
                                @if(in_array($i, [19,20]))
                                <p>{{ trans('main.send_msg_business') }}</p>
                                @endif
                                @if($i >= 21)
                                <p>{{ trans('main.send_msg_groupp1') }}</p>
                                @endif

                                @if($i >= 23)
                                <p>{{ trans('main.send_msg_groupp2') }}</p>
                                @endif

                                <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab1" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="curl-tab-{{$i}}" data-toggle="tab" href="#curl-{{$i}}">
                                            <span class="nav-text">PHP - CURL</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="php-tab-{{$i}}" data-toggle="tab" href="#php-{{$i}}" aria-controls="password">
                                            <span class="nav-text">PHP Class</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="bash-tab-{{$i}}" data-toggle="tab" href="#bash-{{$i}}" aria-controls="payment">
                                            <span class="nav-text">Curl (Bash)</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content mt-5" id="myTabContent1">
                                    <div class="tab-pane fade active show" id="curl-{{$i}}">
                                        <div class="content">
                                            {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/'.$i.'.php'))) }}
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="php-{{$i}}">
                                        <div class="content">
                                            {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/'.$i.'.php'))) }}
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="bash-{{$i}}">
                                        <div class="content">
                                            {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/'.$i.'.php'))) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endfor


    </div>
</div>

@endsection
