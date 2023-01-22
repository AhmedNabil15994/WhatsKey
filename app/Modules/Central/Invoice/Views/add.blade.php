{{-- Extends layout --}}
@extends('central.Layouts.Dashboard.master')
@section('title',$data->designElems['mainData']['title'])

@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="header-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{ $data->designElems['mainData']['title'] }}</h4>
                        </div>
                    </div>
                    <hr>
                    <form class="form-horizontal" method="POST" action="{{ URL::to('/'.$data->designElems['mainData']['url'].'/create') }}">
                        @csrf
                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.client') }} :</label>
                            <div class="col-9">
                                <select name="client_id" class="form-control">
                                    <option value="">{{trans('main.choose')}}</option>
                                    @foreach($data->clients as $client)
                                    <option value="{{$client->id}}" {{old('client_id') == $client->id ? 'selected' : ''}}>{{$client->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.due_date') }} :</label>
                            <div class="col-9">
                                <input type="text" name="due_date" class="form-control datepicker" value="{{old('due_date')}}" placeholder="{{ trans('main.due_date') }}">
                            </div>
                        </div>

                        <input type="hidden" name="total" class="form-control" value="">

                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.status') }} :</label>
                            <div class="col-9">
                                <select name="status" class="form-control" data-toggle="select2">
                                    <option value="">{{ trans('main.choose') }}</option>
                                    @for($i=0; $i<=5; $i++)
                                    <option value="{{ $i }}" {{old('status') == $i ? 'selected' : ''}}>{{ trans('main.invoice_status_'.$i) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.paymentMethod') }} :</label>
                            <div class="col-9">
                                <select name="payment_method" class="form-control" data-toggle="select2">
                                    <option value="">{{ trans('main.choose') }}</option>
                                    <option value="1" {{ old('payment_method') == 1 ? 'selected' : '' }}>{{ trans('main.mada') }}</option>
                                    <option value="2" {{ old('payment_method') == 2 ? 'selected' : '' }}>{{ trans('main.visaMaster') }}</option>
                                    <option value="3" {{ old('payment_method') == 3 ? 'selected' : '' }}>{{ trans('main.bankTransfer') }}</option>

                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="inputEmail3" class="col-3 col-form-label">{{ trans('main.couponCode') }} :</label>
                            <div class="col-9">
                                <select name="coupon_code" class="form-control" data-toggle="select2">
                                    <option value="">{{ trans('main.choose') }}</option>
                                    @foreach($data->coupons as $coupon)
                                    <option value="{{$coupon->id}}" {{ old('coupon_code') == $coupon->id ? 'selected' : '' }}>{{ $coupon->code }}</option>
                                    @endforeach
                                    <option value="2" {{ old('discount_type') == 2 ? 'selected' : '' }}>{{ trans('main.discount_type_2') }}</option>
                                </select>
                            </div>
                        </div>
            
                        <hr>
                        <div class="table-responsive">
                            <h4 class="page-title mb-3">{{ trans('main.invoice_items') }}</h4>
                            <table class="table table-borderless table-centered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ trans('main.item') }}</th>
                                        <th>{{ trans('main.price') }}</th>
                                        <th>{{ trans('main.quantity') }}</th>
                                        <th>{{ trans('main.start_date') }}</th>
                                        <th>{{ trans('main.end_date') }}</th>
                                        <th>{{ trans('main.price_after_vat') }}</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $mainPrices = 0; @endphp
                                    
                                </tbody>
                            </table>
                        </div> <!-- end table-responsive-->

                        <!-- Add note input-->
                        <div class="mt-3">
                            <label for="example-textarea">{{ trans('main.notes') }}:</label>
                            <textarea class="form-control" name="notes" id="example-textarea" rows="3" placeholder="{{ trans('main.notes') }}..">{{ old('notes') }}</textarea>
                        </div>

                        <div class="form-group mt-3 mb-0 row">
                            <div class="col-12 text-right">
                                <button class="btn btn-success AddBTNz" type="submit">{{ trans('main.save') }}</button>
                                <a href="{{ URL::to('/'.$data->designElems['mainData']['url']) }}" type="reset" class="btn btn-danger Reset">{{ trans('main.back') }}</a>
                            </div>
                        </div>
                    </form>
                    <!--end: Datatable-->
                </div> <!-- end card body-->
            </div> <!-- end card -->
        </div><!-- end col-->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="border p-3 mt-4 mt-lg-0 rounded">
                        <h4 class="header-title mb-3">{{ trans('main.order_sum') }}</h4>

                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr>
                                        <td>{{ trans('main.grandTotal') }} :</td>
                                        <td><span class="grandTotal">{{ $mainPrices }}</span> {{ trans('main.sar') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('main.discount') }} : </td>
                                        <td><span class="discount">0</span> {{ trans('main.sar') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ trans('main.estimatedTax') }} : </td>
                                        <td><span class="tax">{{ $mainPrices }}</span> {{ trans('main.sar') }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('main.total') }} :</th>
                                        <th><span class="total">{{ $mainPrices }}</span>  {{ trans('main.sar') }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- end table-responsive -->
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
    <!-- end row-->
</div> <!-- container -->
@endsection

@section('scripts')
<script src="{{ asset('tenancy/assets/components/editInvoice.js') }}" type="text/javascript"></script>
@endsection