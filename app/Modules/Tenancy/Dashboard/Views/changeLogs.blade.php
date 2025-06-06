{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',trans('main.changeLogs'))
@section('pageName',trans('main.changeLogs'))

@section('styles')
<style>
    .form-group.textWrap emoji-picker{
        top: 40px;
    }
    html[dir="ltr"] .form-group.textWrap emoji-picker{
        right: 30px;
    }
    html[dir="rtl"] .form-group.textWrap emoji-picker{
        left: 30px;
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
            'title' => trans('main.helpCenter'),
            'url' => \URL::to('/helpCenter')
        ],
        [
            'title' => trans('main.changeLogs'),
            'url' => \URL::current()
        ],
    ]
])
@endsection

@section('content')
@foreach($data->data as $one)
<div class="card card-custom gutter-b">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="symbol symbol-45 symbol-light mr-5">
                <span class="symbol-label">
                    <span class="svg-icon svg-icon-lg svg-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"></path>
                                <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"></path>
                                <rect fill="#000000" opacity="0.3" x="7" y="10" width="5" height="2" rx="1"></rect>
                                <rect fill="#000000" opacity="0.3" x="7" y="14" width="9" height="2" rx="1"></rect>
                            </g>
                        </svg>
                    </span>
                </span>
            </div>
            <div class="d-flex flex-column flex-grow-1">
                <a href="#" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">{{$one->title}}</a>
                <div class="d-flex">
                    <div class="d-flex align-items-center pr-5">
                        <span class="svg-icon svg-icon-md svg-icon-primary pr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M12,22 C7.02943725,22 3,17.9705627 3,13 C3,8.02943725 7.02943725,4 12,4 C16.9705627,4 21,8.02943725 21,13 C21,17.9705627 16.9705627,22 12,22 Z" fill="#000000" opacity="0.3"></path>
                                    <path d="M11.9630156,7.5 L12.0475062,7.5 C12.3043819,7.5 12.5194647,7.69464724 12.5450248,7.95024814 L13,12.5 L16.2480695,14.3560397 C16.403857,14.4450611 16.5,14.6107328 16.5,14.7901613 L16.5,15 C16.5,15.2109164 16.3290185,15.3818979 16.1181021,15.3818979 C16.0841582,15.3818979 16.0503659,15.3773725 16.0176181,15.3684413 L11.3986612,14.1087258 C11.1672824,14.0456225 11.0132986,13.8271186 11.0316926,13.5879956 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z" fill="#000000"></path>
                                </g>
                            </svg>
                        </span>
                        <span class="text-muted font-weight-bold">{{$one->created_at}}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="svg-icon svg-icon-md svg-icon-primary pr-1">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M5.5,4 L9.5,4 C10.3284271,4 11,4.67157288 11,5.5 L11,6.5 C11,7.32842712 10.3284271,8 9.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,5.5 C4,4.67157288 4.67157288,4 5.5,4 Z M14.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,17.5 C13,16.6715729 13.6715729,16 14.5,16 Z" fill="#000000"></path>
                                    <path d="M5.5,10 L9.5,10 C10.3284271,10 11,10.6715729 11,11.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,11.5 C4,10.6715729 4.67157288,10 5.5,10 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,12.5 C20,13.3284271 19.3284271,14 18.5,14 L14.5,14 C13.6715729,14 13,13.3284271 13,12.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z" fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                        </span>
                        <span class="text-muted font-weight-bold">{{$one->category}}</span>
                    </div>
                </div>
            </div>                        
        </div>
        <div class="pt-3">
            <p class="text-dark-75 font-size-lg font-weight-normal pt-5 mb-6">{{$one->description}}</p>
            @if($one->photo != '')
            <div class="bgi-no-repeat bgi-size-cover rounded min-h-295px" style="background-image: url({{$one->photo}})"></div>
            @endif
        </div>
        <div class="separator separator-solid mt-9 mb-4"></div>
        <form class="position-relative par textWrap form-group">
            <textarea  id="kt_forms_widget_11_input" class="form-control px-2 py-2 p-0 pr-10 resize-none" rows="1" name="reply" placeholder="{{ trans('main.postComment') }}" style="overflow: hidden; overflow-wrap: break-word; min-height: 60px;"></textarea>
            <i class="la la-smile icon-xl emoji-icon"></i>
            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
            <div class="position-absolute top-0 right-0 mt-n1 mr-n2">
                <span class="btn btn-icon btn-sm btn-hover-icon-primary mx-1 mb-1">
                    <i class="flaticon2-send-1 icon-md addRate" data-area="{{$one->id}}"></i>
                </span>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection


@section('scripts')
<script type="module" src="{{asset('assets/tenant/js/emojiIndex.js')}}"></script>
<script src="{{ asset('assets/tenant/components/initEmoji.js') }}"></script>
@endsection
