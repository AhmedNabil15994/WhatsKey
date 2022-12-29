@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style>
    textarea{
        min-height: 150px;
        max-height: 150px;
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
            'title' => trans('main.products'),
            'url' => \URL::to('/'.$data->designElems['mainData']['url'])
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
<div class="d-flex flex-row">
    <div class="flex-row-fluid">
        <div class="row">
            <div class="col-md-7 col-lg-12 col-xxl-7">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-body px-15 pt-15">
                        <div class="row mb-15">
                            <div class="col-xxl-5 mb-11 mb-xxl-0">
                                <div class="card card-custom card-stretch">
                                    <div class="card-body p-0 rounded px-10 py-15 d-flex align-items-center justify-content-center" style="background-color: #FFCC69;">
                                        <img src="{{$data->data->images}}" class="mw-100 w-200px" style="transform: scale(1.6);" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-7 pl-xxl-11">
                                <h2 class="font-weight-bolder text-dark mb-7" style="font-size: 32px;">{{$data->data->name}}</h2>
                                <div class="font-size-h2 mb-7 text-dark-50">From
                                <span class="text-info font-weight-boldest ml-2">{{$data->data->price . ' ' . $data->data->currency}}</span></div>
                                <div class="line-height-xl">{{$data->data->description}}</div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-6 col-md-6">
                                <div class="mb-8 d-flex flex-column">
                                    <span class="text-dark font-weight-bold mb-4">{{trans('main.collection')}}</span>
                                    <span class="text-muted font-weight-bolder font-size-lg">{{$data->data->collection}}</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="mb-8 d-flex flex-column">
                                    <span class="text-dark font-weight-bold mb-4">{{trans('main.availability')}}</span>
                                    <span class="text-muted font-weight-bolder font-size-lg">{{$data->data->availability}}</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="d-flex flex-column">
                                    <span class="text-dark font-weight-bold mb-4">{{trans('main.review_status')}}</span>
                                    <span class="text-muted font-weight-bolder font-size-lg">{{$data->data->review_status}}</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="d-flex flex-column">
                                    <span class="text-dark font-weight-bold mb-4">{{trans('main.isHidden')}}</span>
                                    <span class="text-muted font-weight-bolder font-size-lg">{{$data->data->is_hidden}}</span>
                                </div>
                            </div>
                        </div>
                        @if(\Helper::checkRules('send-'.$data->designElems['mainData']['nameOne']))
                        <div class="card-footer text-right pb-0">
                            <a href="#" data-toggle="modal" data-target="#contactsModal" class="btn btn-light-success btn-sm font-weight-bolder font-size-sm py-3 px-6 sendProduct">
                                <i class="flaticon2-send-1"></i> {{trans('main.sendProduct')}} 
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-5 col-lg-12 col-xxl-5">
                <div class="card card-custom card-stretch gutter-b">
                    <div class="card-header border-0 pt-6 mb-2">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">{{trans('main.latestProducts')}}</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    @foreach($data->latest as $lastOne)
                                    <tr>
                                        <td class="align-middle w-50px pl-0 pr-2 pb-6">
                                            <div class="symbol symbol-50 symbol-light-success">
                                                <div class="symbol-label" style="background-image: url('{{$lastOne->images}}')"></div>
                                            </div>
                                        </td>
                                        <td class="align-middle pb-6">
                                            <a href="{{URL::to('/products/view/'.$lastOne->id)}}">
                                                <div class="font-size-lg font-weight-bolder text-dark-75 mb-1">{{$lastOne->name}}</div>
                                                <div class="font-weight-bold text-muted">{{$lastOne->description}}</div>
                                            </a>
                                        </td>
                                        <td class="text-right align-middle pb-6">
                                            <div class="font-weight-bold text-muted mb-1">{{trans('main.total')}}</div>
                                            <div class="font-size-lg font-weight-bolder text-dark-75">{{$lastOne->price . ' ' . $lastOne->currency}}</div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-custom">
            <div class="card-header border-0 py-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label font-weight-bolder text-dark">{{trans('main.lastOrders')}}</span>
                </h3>
            </div>
            <div class="card-body py-0">
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_4">
                        <thead>
                            <tr class="text-left">
                                <th class="pl-0" style="width: 30px">
                                    <label class="checkbox checkbox-lg checkbox-inline mr-2">
                                        <input type="checkbox" value="1" />
                                        <span></span>
                                    </label>
                                </th>
                                <th class="pl-0" style="min-width: 120px">{{trans('main.order_id')}}</th>
                                <th style="min-width: 110px">{{trans('main.order_token')}}</th>
                                <th style="min-width: 110px">{{trans('main.title')}}</th>
                                <th style="min-width: 120px">{{trans('main.productsCount')}}</th>
                                <th style="min-width: 120px">{{trans('main.price')}}</th>
                                <th style="min-width: 120px">{{trans('main.currency')}}</th>
                                <th style="min-width: 120px">{{trans('main.phone')}}</th>
                                <th class="pr-0 text-right">{{trans('main.actions')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data->lastOrders as $oneOrder)
                            <tr>
                                <td class="pl-0 py-6">
                                    <label class="checkbox checkbox-lg checkbox-inline">
                                        <input type="checkbox" value="1" />
                                        <span></span>
                                    </label>
                                </td>
                                <td class="pl-0">
                                    <a href="#" class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg">{{$oneOrder->title}}</a>
                                </td>
                                <td>
                                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{$oneOrder->token}}</span>
                                </td>
                                <td>
                                    <span class="text-primary font-weight-bolder d-block font-size-lg">{{$oneOrder->title}}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{$oneOrder->itemCount}}</span>
                                </td>
                                <td>
                                    <span class="label label-lg label-light-primary label-inline">{{$oneOrder->price}}</span>
                                </td>
                                <td>
                                    <span class="label label-lg label-light-primary label-inline">{{$oneOrder->currency}}</span>
                                </td>
                                <td>
                                    <span class="label label-lg label-light-primary label-inline">{{$oneOrder->chatId}}</span>
                                </td>
                                <td class="pr-0 text-right">
                                    <a href="{{URL::to('/orders/view/'.$oneOrder->id)}}" class="btn btn-icon btn-light btn-hover-primary btn-sm">
                                        <span class="svg-icon svg-icon-md svg-icon-primary">
                                            <i class="la la-eye icon-md"></i>
                                        </span>
                                    </a>
                                </td>
                            </tr>  
                            @endforeach        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<div class="modal fade" id="contactsModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{trans('main.contacts')}}</h5>
                <button class="btn" type="button" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true close-btn"><i class="fa fa-times icon-xl"></i></span>
                </button>
            </div>
            <div class="modal-body px-10 py-10">
                <div class="form-group">
                    <label>{{trans('main.numbers')}}</label>
                    <select name="participants_type" class="form-control" data-toggle="select2">
                        <option value="1">{{trans('main.contacts')}}</option>
                        <option value="2">{{trans('main.newContacts')}}</option>
                    </select>
                </div>
                <div class="form-group" data-id="1">
                    <label>{{trans('main.numbers')}}</label>
                    <select name="participantsPhone[]" class="form-control" data-toggle="select2" multiple>
                        @foreach($data->contacts as $contact)
                        <option value="{{ str_replace('+','',$contact->phone) }}">{{ $contact->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" data-id="2" style="display: none;">
                    <label>{{trans('main.numbers')}}</label>
                    <textarea class="form-control" name="participants" placeholder="{{ trans('main.whatsappNos2') }}"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success selectProductContacts">{{trans('main.send')}}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/tenant/components/viewProduct.js')}}"></script>
@endsection
