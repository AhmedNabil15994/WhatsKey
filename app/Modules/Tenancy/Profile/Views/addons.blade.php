{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',trans('main.addons'))
@section('pageName',trans('main.addons'))

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
            'title' => trans('main.addons'),
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
<div class="row">
    <div class="col-8">
        <div class="card">
            <div class="position-absolute w-100 h-50 rounded-card-top" style="background-color: #22B9FF"></div>
            <div class="card-body position-relative">
                <h3 class="7 text-white text-center my-10 my-lg-15">{{trans('main.resubscribe_b2') . ' - ' . trans('main.addons')}}</h3>
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
                                        @foreach($data->addons as $key => $membership)
                                        @if($key % 4 == 0 && $key != 0)
                                        </div></div><div class="carousel-item"> <div class="row mb-5">
                                        @endif
                                        <div class="col-md-3 p-0 package">
                                            <div class="pt-30 card mx-2 pt-md-25  text-center">
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
                                                                <path d="M7.07744993,12.3040451 C7.72444571,13.0716094 8.54044565,13.6920474 9.46808594,14.1079953 L5,23 L4.5,18 L7.07744993,12.3040451 Z M14.5865511,14.2597864 C15.5319561,13.9019016 16.375416,13.3366121 17.0614026,12.6194459 L19.5,18 L19,23 L14.5865511,14.2597864 Z M12,3.55271368e-14 C12.8284271,3.53749572e-14 13.5,0.671572875 13.5,1.5 L13.5,4 L10.5,4 L10.5,1.5 C10.5,0.671572875 11.1715729,3.56793164e-14 12,3.55271368e-14 Z" fill="#000000" opacity="0.3"></path>
                                                                <path d="M12,10 C13.1045695,10 14,9.1045695 14,8 C14,6.8954305 13.1045695,6 12,6 C10.8954305,6 10,6.8954305 10,8 C10,9.1045695 10.8954305,10 12,10 Z M12,13 C9.23857625,13 7,10.7614237 7,8 C7,5.23857625 9.23857625,3 12,3 C14.7614237,3 17,5.23857625 17,8 C17,10.7614237 14.7614237,13 12,13 Z" fill="#000000" fill-rule="nonzero"></path>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </div>
                                                <h4 class="font-size-h3 mb-10 item_title">{{$membership->title}}</h4>
                                                <span class="font-size-h1 d-block font-weight-boldest text-dark price" data-monthly="{{$membership->monthly_after_vat}}" data-annual="{{$membership->annual_after_vat}}">
                                                    <span class="value item_price">{{$membership->monthly_after_vat}}</span>
                                                    <sup class="font-size-h3 font-weight-normal pl-1">{{trans('main.sar2')}}</sup>
                                                </span>
                                                <div class="mt-7">
                                                    <a href="#" data-area="1" data-tab="{{$membership->id}}" class="mediaBtn btn btn-primary text-uppercase font-weight-bolder px-15 py-3">{{trans('main.addToCart')}}</a>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>  
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
    </div>
    <div class="col-4">
        <div class="card card-custom gutter-b">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title mb-5 w-100">
                    <span class="card-label font-weight-bolder text-dark mb-1 float-left d-inline-block w-75">{{trans('main.myCart')}} </span>
                    <span class="float-right d-inline-block w-25 text-right">(<span class="itemCount">0</span>)</span>
                    <div class="clearfix"></div>
                </h3>
            </div>
            <div class="card-body pt-2">
                <div class="cartItems mb-5"></div>
                <div class="d-block w-100 clearfix mb-3">
                    <span class="font-size-h6 float-left text-left d-inline-block w-75">{{trans('main.grandTotal')}}</span>
                    <span class="font-weight-bolder float-right font-size-h6 text-right d-inline-block w-25"> <span class="grandTotal">00.00</span> <sup>{{trans('main.sar2')}}</sup> </span>
                </div>
                <div class="d-block w-100 clearfix mb-3">
                    <span class="font-size-h6 float-left text-left d-inline-block w-75">{{trans('main.estimatedTax')}}</span>
                    <span class="font-weight-bolder float-right font-size-h6 text-right d-inline-block w-25"> <span class="tax">00.00</span> <sup>{{trans('main.sar2')}}</sup> </span>
                </div>
                <div class="d-block w-100 clearfix">
                    <span class="font-weight-bolder float-left font-size-h4 text-left d-inline-block w-75">{{trans('main.subTotal')}}</span>
                    <span class="font-weight-bolder float-right font-size-h4 text-right d-inline-block w-25"> <span class="total">00.00</span> <sup>{{trans('main.sar2')}}</sup> </span>
                </div>
                <div class="d-block w-100 clearfix mb-10">
                    <span class="border-0 text-muted text-left pt-0">{{trans('main.taxesIncluded')}}</span>
                </div>
                <form action="{{URL::current()}}" method="post">
                    @csrf
                    <input type="hidden" name="addonData">
                    <button class="btn btn-success float-right checkout" type="submit">{{trans('main.checkout')}}</button>                
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
@endsection


@section('scripts')
<script src="{{asset('assets/tenant/components/addons.js')}}"></script>
@endsection
