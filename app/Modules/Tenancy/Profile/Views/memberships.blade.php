{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',trans('main.memberships'))
@section('pageName',trans('main.memberships'))

@section('styles')
<style type="text/css">
    .carousel-control-next-icon, .carousel-control-prev-icon{
        width: 1rem;
        border: 0;
        height: 1rem;
    }
    .carousel-control-next{
        left: calc(100% - 50px);
        top: calc(50% - 25px);
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }
    .carousel-control-prev{
        right: calc(100% - 50px);
        top: calc(50% - 25px);
        width: 30px;
        height: 30px;
        border-radius: 50%;
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
            'title' => trans('main.myAccount'),
            'url' => \URL::to('/profile/subscription')
        ],
        [
            'title' => trans('main.memberships'),
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
<div class="card">
    <div class="position-absolute w-100 h-50 rounded-card-top" style="background-color: #22B9FF"></div>
    <div class="card-body position-relative">
        <h3 class="7 text-white text-center my-10 my-lg-15">{{trans('main.resubscribe_b2') . ' - ' . trans('main.memberships')}}</h3>
        <div class="d-flex justify-content-center">
            <ul class="nav nav-pills nav-primary mb-10 mb-lg-20 bg-white rounded" id="pills-tab" role="tablist">
                <li class="nav-item p-0 m-0 pkgDates">
                    <a class="period nav-link monthly active font-weight-bolder rounded-right-0 px-8 py-5" id="pills-tab-1" data-toggle="pill" href="#kt-pricing-2_content1" aria-expanded="true" aria-controls="kt-pricing-2_content1">{{trans('main.monthly')}}</a>
                </li>
                <li class="nav-item p-0 m-0 pkgDates">
                    <a class="period nav-link yearly font-weight-bolder rounded-left-0 px-8 py-5" id="pills-tab-1" data-toggle="pill" href="#kt-pricing-2_content1" aria-expanded="true" aria-controls="kt-pricing-2_content1">{{trans('main.yearly')}}</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div class="tab-pane show active row text-center" id="kt-pricing-2_content1" role="tabpanel" aria-labelledby="pills-tab-1">
                <div id="carouselExampleCaptionss" class="carousel slide" data-bs-ride="true">
                    <div class="carousel-inner">
                        <div class="col-11 col-lg-12 col-xxl-10 mx-auto">
                            <div class="carousel-item active"> 
                                <div class="row mb-5">
                                @foreach($data->memberships as $key => $membership)
                                @if($key % 4 == 0 && $key != 0)
                                </div></div><div class="carousel-item"> <div class="row mb-5">
                                @endif
                                <div class="col-md-3 p-0 package">
                                    <div class="pt-30 card mx-2 pt-md-25 pb-15 px-5 text-center">
                                        <div class="d-flex flex-center position-relative mb-25">
                                            <span class="svg svg-fill-primary opacity-4 position-absolute">
                                                <svg width="175" height="200">
                                                    <polyline points="87,0 174,50 174,150 87,200 0,150 0,50 87,0"></polyline>
                                                </svg>
                                            </span>
                                            <span class="svg-icon svg-icon-5x svg-icon-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"></rect>
                                                        <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                                        <path d="M3.28077641,9 L20.7192236,9 C21.2715083,9 21.7192236,9.44771525 21.7192236,10 C21.7192236,10.0817618 21.7091962,10.163215 21.6893661,10.2425356 L19.5680983,18.7276069 C19.234223,20.0631079 18.0342737,21 16.6576708,21 L7.34232922,21 C5.96572629,21 4.76577697,20.0631079 4.43190172,18.7276069 L2.31063391,10.2425356 C2.17668518,9.70674072 2.50244587,9.16380623 3.03824078,9.0298575 C3.11756139,9.01002735 3.1990146,9 3.28077641,9 Z M12,12 C11.4477153,12 11,12.4477153 11,13 L11,17 C11,17.5522847 11.4477153,18 12,18 C12.5522847,18 13,17.5522847 13,17 L13,13 C13,12.4477153 12.5522847,12 12,12 Z M6.96472382,12.1362967 C6.43125772,12.2792385 6.11467523,12.8275755 6.25761704,13.3610416 L7.29289322,17.2247449 C7.43583503,17.758211 7.98417199,18.0747935 8.51763809,17.9318517 C9.05110419,17.7889098 9.36768668,17.2405729 9.22474487,16.7071068 L8.18946869,12.8434035 C8.04652688,12.3099374 7.49818992,11.9933549 6.96472382,12.1362967 Z M17.0352762,12.1362967 C16.5018101,11.9933549 15.9534731,12.3099374 15.8105313,12.8434035 L14.7752551,16.7071068 C14.6323133,17.2405729 14.9488958,17.7889098 15.4823619,17.9318517 C16.015828,18.0747935 16.564165,17.758211 16.7071068,17.2247449 L17.742383,13.3610416 C17.8853248,12.8275755 17.5687423,12.2792385 17.0352762,12.1362967 Z" fill="#000000"></path>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                        <h4 class="font-size-h3 mb-10">{{$membership->title}}</h4>
                                        <div class="d-flex flex-column line-height-xl pb-10">
                                            @foreach($membership->featruesArr as $feature)
                                            <span>{{$feature}}</span>
                                            @endforeach
                                        </div>
                                        <span class="font-size-h1 d-block font-weight-boldest text-dark price" data-monthly="{{$membership->monthly_after_vat}}" data-annual="{{$membership->annual_after_vat}}">
                                            <span class="value">{{$membership->monthly_after_vat}}</span>
                                            <sup class="font-size-h3 font-weight-normal pl-1">{{trans('main.sar2')}}</sup>
                                        </span>
                                        <div class="mt-7">
                                            <a href="{{URL::to('/profile/subscription/memberships/updateMembership?membership='.$membership->id.'&duration=1')}}" class="mediaBtn btn btn-primary text-uppercase font-weight-bolder px-15 py-3">{{trans('main.checkout')}}</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev btn-icon btn-dark" href="#carouselExampleCaptionss" role="button" data-slide="prev">
                        <i class="fa fa-angle-left"></i>
                    </a>
                    <a class="carousel-control-next btn-icon btn-dark" href="#carouselExampleCaptionss" role="button" data-slide="next">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
@endsection


@section('scripts')
<script>
    $(function(){
        $('.period').on('click',function(){
            let periodItem = $(this); 
            $.each($('.package'),function(index,item){
                let aHref = $(item).find('.mediaBtn').attr('href');
                if(periodItem.hasClass('monthly')){
                    $(item).find('.price').children('span.value').text($(item).find('.price').data('monthly'));
                    $(item).find('.mediaBtn').attr('href',aHref.replace('&duration=2','&duration=1'));
                }else{
                    $(item).find('.price').children('span.value').text($(item).find('.price').data('annual'));
                    $(item).find('.mediaBtn').attr('href',aHref.replace('&duration=1','&duration=2'));
                }
            })
            $(this).addClass('active').parent('li').siblings('li').children('.period.active').removeClass('active');
        });
    });
</script>
@endsection
