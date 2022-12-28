@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="print">
    #kt_header,.subheader,#kt_header_mobile,.alert,.inv-footer,.footer,#kt_scrolltop{
        display: none !important;
    }
    .header-fixed.subheader-fixed.subheader-enabled .wrapper{
        padding-top: 0;
        padding-bottom: 0;
        background-color: #FFF;
    }
    .content{
        padding-bottom: 0;
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
            'title' => trans('main.invoices'),
            'url' => \URL::to('/invoices')
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection
@section('content')
<div class="card card-custom">
    <div class="card-body p-0">
        <div class="row justify-content-center py-8 px-8 py-md-27 px-md-0">
            <div class="col-md-10">
                <div class="d-flex justify-content-between pb-10 pb-md-20 flex-column flex-md-row">
                    <h1 class="display-4 font-weight-boldest mb-10">{{trans('main.order_details')}}</h1>
                    <div class="d-flex flex-column align-items-md-end px-0">
                        <!--begin::Logo-->
                        <a href="#" class="mb-5">
                            <img src="{{asset('/assets/images/green logo -png.png')}}" class="w-250px" />
                        </a>
                        <!--end::Logo-->
                        <span class="d-flex flex-column align-items-md-end opacity-70">
                            <span>{{str_replace('@c.us','',$data->data->sellerJid)}}</span>
                            <span>{{ucwords(\App\Models\User::first()->name)}}</span>
                        </span>
                    </div>
                </div>
                <div class="border-bottom w-100"></div>
                <div class="d-flex justify-content-between pt-6">
                    <div class="d-flex flex-column flex-root">
                        <span class="font-weight-bolder mb-2">{{trans('main.order_date')}}</span>
                        <span class="opacity-70">{{$data->data->created_at}}</span>
                    </div>
                    <div class="d-flex flex-column flex-root">
                        <span class="font-weight-bolder mb-2">{{trans('main.order_no')}}.</span>
                        <span class="opacity-70">{{$data->data->order_id}}</span>
                    </div>
                    <div class="d-flex flex-column flex-root">
                        <span class="font-weight-bolder mb-2">{{trans('main.createdFor')}}.</span>
                        <span class="opacity-70">{{$data->data->title}}.
                        <br />{{$data->data->chatId}}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- end: Invoice header-->
        <!-- begin: Invoice body-->
        <div class="row justify-content-center py-8 px-8 py-md-10 px-md-0">
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="pl-0 font-weight-bold text-muted text-uppercase">{{ trans('main.orderItems') }}</th>
                                <th class="text-right font-weight-bold text-muted text-uppercase">{{ trans('main.quantity') }}</th>
                                <th class="text-right font-weight-bold text-muted text-uppercase">{{ trans('main.unitPrice') }}</th>
                                <th class="text-right pr-0 font-weight-bold text-muted text-uppercase">{{ trans('main.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->data->products as $product)
                            <tr class="font-weight-boldest">
                                <td class="border-0 pl-0 pt-7 d-flex align-items-center">
                                    <div class="symbol symbol-40 flex-shrink-0 mr-4 bg-light">
                                        <div class="symbol-label" style="background-image: url('{{$product->imageUrl}}')"></div>
                                    </div>
                                    {{$product->name}}
                                </td>
                                <td class="text-right pt-7 align-middle">{{$product->quantity}}</td>
                                <td class="text-right pt-7 align-middle">{{($product->price/1000) . ' ' . $product->currency}}</td>
                                <td class="text-primary pr-0 pt-7 text-right align-middle">{{ ($product->quantity * $product->price / 1000) . ' ' . $product->currency }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end: Invoice body-->
        <!-- begin: Invoice footer-->
        <div class="row justify-content-center bg-gray-100 py-8 px-8 py-md-10 px-md-0 mx-0">
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="font-weight-bold text-muted text-uppercase">{{ trans('main.paymentGateaway') }}</th>
                                <th class="font-weight-bold text-muted text-uppercase">{{ trans('main.status') }}</th>
                                <th class="font-weight-bold text-muted text-uppercase">{{ trans('main.transaction_date') }}</th>
                                <th class="font-weight-bold text-muted text-uppercase text-right">{{ trans('main.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="font-weight-bolder">
                                <td>------</td>
                                <td>Pending</td>
                                <td>------</td>
                                <td class="text-primary font-size-h3 font-weight-boldest text-right">{{$data->data->price . ' ' . $data->data->currency}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end: Invoice footer-->
        
        <!-- begin: Invoice action-->
        <div class="py-4 px-4 text-right inv-footer">
            <div class="d-block font-size-sm">
                <button type="button" class="btn btn-primary font-weight-bolder mr-3 my-1" onclick="window.print();">{{ trans('main.print') }}</button>
                <a href="{{ URL::to('/orders') }}" class="btn btn-secondary font-weight-bolder ml-sm-auto my-1">{{ trans('main.back') }}</a>
            </div>
        </div>
        <!-- end: Invoice action-->
        <!--end::Invoice-->
    </div>
</div>
@endsection
@section('scripts')

@endsection
