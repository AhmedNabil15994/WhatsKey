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
        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample1">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne1">
                        {{ trans('main.send_text') }}
                    </div>
                </div>
                <div id="collapseOne1" class="collapse show" data-parent="#accordionExample1">
                    <div class="card-body">
                        <div class="example-preview">
                            <p>{{ trans('main.send_text_p1') }}</p>
                            <p>{{ trans('main.send_text_p2') }}</p>
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-1" data-toggle="tab" href="#curl-1">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-1" data-toggle="tab" href="#php-1" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-1" data-toggle="tab" href="#bash-1" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent1">
                                <div class="tab-pane fade active show" id="curl-1" role="tabpanel" aria-labelledby="curl-tab-1">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendMessage.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-1" role="tabpanel" aria-labelledby="php-tab-1">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendMessage.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-1" role="tabpanel" aria-labelledby="bash-tab-1">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendMessage.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample2">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne2">
                        {{ trans('main.send_file') }}
                    </div>
                </div>
                <div id="collapseOne2" class="collapse" data-parent="#accordionExample2">
                    <div class="card-body">
                        <div class="example-preview">
                            <p>{{ trans('main.send_file_p1') }}</p>
                            <p>{{ trans('main.send_file_p2') }}</p>
                            <p>{{ trans('main.send_file_p3') }}</p>
                            <p>{{ trans('main.send_text_p2') }}</p>
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-2" data-toggle="tab" href="#curl-2">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-2" data-toggle="tab" href="#php-2" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-2" data-toggle="tab" href="#bash-2" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent2">
                                <div class="tab-pane fade active show" id="curl-2" role="tabpanel" aria-labelledby="curl-tab-2">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendFile.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-2" role="tabpanel" aria-labelledby="php-tab-2">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendFile.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-2" role="tabpanel" aria-labelledby="bash-tab-2">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendFile.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample3">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne3">
                        {{ trans('main.send_sound') }}
                    </div>
                </div>
                <div id="collapseOne3" class="collapse" data-parent="#accordionExample3">
                    <div class="card-body">
                        <div class="example-preview">
                            <p>{{ trans('main.send_sound_p1') }}</p>
                            <p>{{ trans('main.send_sound_p2') }}</p>
                            <a href="https://audio.online-convert.com/convert-to-ogg" target="_blank">https://audio.online-convert.com/convert-to-ogg</a>
                            <p>{{ trans('main.send_file_p3') }}</p>
                            <p>{{ trans('main.send_sound_p3') }}</p>
                            <img src="{{ asset('assets/tenant/images/soundTips.png') }}" alt="">
                            <p>{{ trans('main.send_file_p3') }}</p>
                            <p>{{ trans('main.send_text_p2') }}</p>
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-3" data-toggle="tab" href="#curl-3">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-3" data-toggle="tab" href="#php-3" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-3" data-toggle="tab" href="#bash-3" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent3">
                                <div class="tab-pane fade active show" id="curl-3" role="tabpanel" aria-labelledby="curl-tab-3">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendPTT.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-3" role="tabpanel" aria-labelledby="php-tab-3">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendPTT.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-3" role="tabpanel" aria-labelledby="bash-tab-3">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendPTT.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne4">
                        {{ trans('main.send_location') }}
                    </div>
                </div>
                <div id="collapseOne4" class="collapse" data-parent="#accordionExample4">
                    <div class="card-body">
                        <div class="example-preview">
                            <p>{{ trans('main.send_location_p1') }}</p>
                            <span>{{ trans('main.example') }}</span> : <a href="https://www.google.com.eg/maps/place/Digital+Servers+Center/@21.5982195,39.1586724,17z/data=!3m1!4b1!4m5!3m4!1s0x15c3d09b97e2fb0d:0x3bafaf5c1752cb0c!8m2!3d21.5982195!4d39.1608611" target="_blank">https://www.google.com.eg/maps/place/Digital+Servers+Center/@21.5982195,39.1586724,17z/data=!3m1!4b1!4m5!3m4!1s0x15c3d09b97e2fb0d:0x3bafaf5c1752cb0c!8m2!3d21.5982195!4d39.1608611</a>
                            <p>{{ trans('main.send_location_p2') }}</p>
                            <p>{{ trans('main.send_location_p3') }}</p>
                            <p>{{ trans('main.send_text_p2') }}</p>
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab4" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-4" data-toggle="tab" href="#curl-4">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-4" data-toggle="tab" href="#php-4" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-4" data-toggle="tab" href="#bash-3" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent4">
                                <div class="tab-pane fade active show" id="curl-4" role="tabpanel" aria-labelledby="curl-tab-4">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendLocation.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-4" role="tabpanel" aria-labelledby="php-tab-4">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendLocation.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-4" role="tabpanel" aria-labelledby="bash-tab-4">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendLocation.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample9">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne9">
                        {{ trans('main.send_contact') }}
                    </div>
                </div>
                <div id="collapseOne9" class="collapse" data-parent="#accordionExample9">
                    <div class="card-body">
                        <div class="example-preview">
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-9" data-toggle="tab" href="#curl-9">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-9" data-toggle="tab" href="#php-9" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-9" data-toggle="tab" href="#bash-9" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent3">
                                <div class="tab-pane fade active show" id="curl-9" role="tabpanel" aria-labelledby="curl-tab-9">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendContact.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-9" role="tabpanel" aria-labelledby="php-tab-9">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendContact.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-9" role="tabpanel" aria-labelledby="bash-tab-9">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendContact.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne5">
                        {{ trans('main.send_link') }}
                    </div>
                </div>
                <div id="collapseOne5" class="collapse" data-parent="#accordionExample5">
                    <div class="card-body">
                        <div class="example-preview">
                            <p>{{ trans('main.send_link_p1') }}</p>
                            <p>{{ trans('main.send_link_p2') }}</p>
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab5" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-5" data-toggle="tab" href="#curl-5">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-5" data-toggle="tab" href="#php-5" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-5" data-toggle="tab" href="#bash-5" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent5">
                                <div class="tab-pane fade active show" id="curl-5" role="tabpanel" aria-labelledby="curl-tab-5">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendLink.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-5" role="tabpanel" aria-labelledby="php-tab-5">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendLink.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-5" role="tabpanel" aria-labelledby="bash-tab-5">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendLink.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne6">
                        {{ trans('main.send_buttons') }}
                    </div>
                </div>
                <div id="collapseOne6" class="collapse" data-parent="#accordionExample6">
                    <div class="card-body">
                        <div class="example-preview">
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab6" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-6" data-toggle="tab" href="#curl-6">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-6" data-toggle="tab" href="#php-6" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-6" data-toggle="tab" href="#bash-6" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent6">
                                <div class="tab-pane fade active show" id="curl-6" role="tabpanel" aria-labelledby="curl-tab-6">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendButtons.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-6" role="tabpanel" aria-labelledby="php-tab-6">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendButtons.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-6" role="tabpanel" aria-labelledby="bash-tab-6">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendButtons.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample7">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne7">
                        {{ trans('main.send_templates') }}
                    </div>
                </div>
                <div id="collapseOne7" class="collapse" data-parent="#accordionExample7">
                    <div class="card-body">
                        <div class="example-preview">
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab7" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-7" data-toggle="tab" href="#curl-7">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-7" data-toggle="tab" href="#php-7" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-7" data-toggle="tab" href="#bash-7" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent7">
                                <div class="tab-pane fade active show" id="curl-7" role="tabpanel" aria-labelledby="curl-tab-7">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendTemplates.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-7" role="tabpanel" aria-labelledby="php-tab-7">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendTemplates.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-7" role="tabpanel" aria-labelledby="bash-tab-7">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendTemplates.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion accordion-toggle-arrow mb-5" id="accordionExample8">
            <div class="card">
                <div class="card-header">
                    <div class="card-title" data-toggle="collapse" data-target="#collapseOne8">
                        {{ trans('main.send_list') }}
                    </div>
                </div>
                <div id="collapseOne8" class="collapse" data-parent="#accordionExample8">
                    <div class="card-body">
                        <div class="example-preview">
                            <ul class="nav nav-light-success nav-pills btnsTabs" id="myTab8" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="curl-tab-8" data-toggle="tab" href="#curl-8">
                                        <span class="nav-text">PHP - CURL</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="php-tab-8" data-toggle="tab" href="#php-8" aria-controls="password">
                                        <span class="nav-text">PHP Class</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="bash-tab-8" data-toggle="tab" href="#bash-8" aria-controls="payment">
                                        <span class="nav-text">Curl (Bash)</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-5" id="myTabContent8">
                                <div class="tab-pane fade active show" id="curl-8" role="tabpanel" aria-labelledby="curl-tab-8">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpCurl/sendList.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="php-8" role="tabpanel" aria-labelledby="php-tab-8">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/phpClass/sendList.php'))) }}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bash-8" role="tabpanel" aria-labelledby="bash-tab-8">
                                    <div class="content">
                                        {{ preg_replace('/\b\d+\b/', '', highlight_file(public_path('assets/tenant/codes/curl/sendList.php'))) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
