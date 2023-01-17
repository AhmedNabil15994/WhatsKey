@extends('tenant.Layouts.master')
@section('title',trans('main.prepareAccount'))
@section('pageName',trans('main.prepareAccount'))
@section('styles')
<link rel="stylesheet" href="{{asset('assets/tenant/css/pages/wizard/wizard-1.css')}}">
<style type="text/css" media="screen">
    textarea{
        min-height: 200px;
    }
    textarea.form-control{
        padding:20px;
        resize:none;
        background: #f7f7f7!important;
        border: 1px solid #eee!important;
    }
    .textLeft{
        text-align:left;
    }
    hr{
        margin:40px 0;
    }
    .boldText{
        font-family: 'Tajawal';
    }
    .botStyle .settings .attention{
        z-index:9;
    }
</style>
@endsection


{{-- Content --}}
@section('content')
<div class="card card-custom">
    <input type="hidden" name="oldName" value="{{ $data->channelName }}">
    <div class="card-body p-0">
        <div class="wizard wizard-1" id="kt_wizard" data-wizard-state="step-first" data-wizard-clickable="false">
            <div class="wizard-nav border-bottom">
                <div class="wizard-steps p-8 p-lg-10">
                    <div class="wizard-step" data-wizard-type="step" data-wizard-state="current">
                        <div class="wizard-label">
                            <i class="wizard-icon flaticon-whatsapp"></i>
                            <h3 class="wizard-title">1. {{ trans('main.channelConfig') }}</h3>
                        </div>
                        <span class="svg-icon svg-icon-xl wizard-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                </g>
                            </svg>
                        </span>
                    </div>
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-label">
                            <i class="wizard-icon flaticon-globe"></i>
                            <h3 class="wizard-title">2. {{ trans('main.qrScan') }}</h3>
                        </div>
                        <span class="svg-icon svg-icon-xl wizard-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000)" x="11" y="5" width="2" height="14" rx="1" />
                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997)" />
                                </g>
                            </svg>
                        </span>
                    </div>
                    <div class="wizard-step" data-wizard-type="step">
                        <div class="wizard-label">
                            <i class="wizard-icon flaticon-rocket"></i>
                            <h3 class="wizard-title">3. {{ trans('main.congratulations') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center my-10 px-8 my-lg-15 px-lg-10">
                <div class="col-xl-12">
                    <div class="form" id="kt_form">
                        <div id="step1" class="pb-5 active" data-wizard-type="step-content" data-wizard-state="current">
                            <h3 class="mb-10 font-weight-bold text-dark">{{ trans('main.channelConfig') }}</h3>
                            <div class="form-group">
                                <label>{{ trans('main.channelName') }}</label>
                                <input type="text" name="channelName" class="form-control form-control-solid form-control-lg" name="address1" placeholder="{{ trans('main.channelName') }}" value="{{ $data->channelName }}" />
                            </div>
                        </div>
                        <div id="step2" class="pb-5" data-wizard-type="step-content">
                            <div class="settings">
                                <h4 class="mb-10 font-weight-bold text-dark">{{ trans('main.qrScan') }}</h4>
                                <div class="alert alert-custom alert-secondary" role="alert">
                                    <div class="alert-icon">
                                        <i class="flaticon-questions-circular-button"></i>
                                    </div>
                                    <div class="alert-text"> {{ trans('main.alert1') }}<span> WhatsApp Web.</span></div>
                                </div>
                                <div class="qr">
                                    <div class="row">
                                        <div class="col-md-4 QrAddLogo">
                                            @livewire('qr-image')
                                        </div>
                                        <div class="col-md-4 mt-5">
                                            <span class="stepQr">{{ trans('main.alert2') }}</span>
                                            <span class="stepQr">{{ trans('main.alert3') }}</span>
                                            <span class="stepQr">{{ trans('main.alert4') }}</span>
                                            <span class="stepQr">{{ trans('main.alert5') }}</span>
                                            <span class="stepQr">{{ trans('main.alert6') }}</span>
                                            <span class="stepQr">{{ trans('main.alert7') }}</span>
                                        </div>
                                        <div class="col-md-4 mt-5">
                                            <img src="{{ asset('assets/tenant/images/scanQR.gif') }}" class="imgPhone" alt="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="settings mt-5">
                                <div class="orangeBg">
                                    <h2 class="titleSettings">{{ trans('main.instructions') }}</h2>
                                    <ul class="list">
                                        <li>{{ trans('main.tip11') }}</li>
                                        <li>{{ trans('main.tip21') }}</li>
                                        <li>{{ trans('main.tip31') }}</li>
                                        <li>{{ trans('main.tip41') }}</li>
                                        <li>{{ trans('main.tip51') }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="settings clearfix mt-5">
                                    <a data-effect="effect-sign" class="btn btn-light-info" data-toggle="modal" data-target="#tipsModal" data-backdrop="static">{{ trans('main.bestTips') }}</a>
                                    <a data-effect="effect-sign" class="btn btn-light-warning" data-toggle="modal" data-target="#termsModal" data-backdrop="static">{{ trans('main.conditions') }}</a>
                                </ul>
                            </div>
                        </div>
                        <div id="step3" class="pb-5" data-wizard-type="step-content">
                            <h4 class="mb-10 font-weight-bold text-dark">{{ trans('main.congratulations') }}</h4>
                            <div class="settings">
                                <div class="alert alert-custom alert-success" role="alert">
                                    <div class="alert-icon">
                                        <i class="flaticon-questions-circular-button"></i>
                                    </div>
                                    <div class="alert-text"> 
                                        {{ trans('main.succ1') }} <br>
                                        {{ trans('main.succ2') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between border-top mt-5 pt-10">
                            <div class="mr-2">
                                <button type="button" class="btn btn-light-primary font-weight-bolder text-uppercase px-9 py-4 btnPrev dis" data-wizard-type="action-prev">{{trans('main.prev')}}</button>
                            </div>
                            <div>
                                <button type="button" class="btn btn-success font-weight-bolder text-uppercase px-9 py-4 finish" data-wizard-type="action-submit">{{trans('main.finish')}}</button>
                                <button type="button" class="btn btn-primary font-weight-bolder text-uppercase px-9 py-4 btnNext" data-wizard-type="action-next">{{trans('main.next')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('modals')
@include('tenant.Partials.tipsModal')
@include('tenant.Partials.termsModal')
@endsection

{{-- Scripts Section --}}

@section('scripts')
<script src="{{ asset('assets/tenant/components/steps.js') }}" type="text/javascript"></script>
<script>
    Livewire.on('statusChanged', postId => {
        document.querySelector('#kt_form .btnNext').click();
        window.location.href= '/dashboard';
    })
</script>

@endsection
