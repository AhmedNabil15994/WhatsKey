{{-- Extends layout --}}
@extends('tenant.Layouts.master')
@section('title',$data->designElems['mainData']['title'])
@section('pageName',$data->designElems['mainData']['title'])

@section('styles')
<style>
    ul li{
        font-size: 15px;
        margin-bottom: 5px;
    }
    ul li span{
        font-size: 14px;
        font-weight: bold;
    }
    #commentFile{
        display: none;
    }
    span.actions{
        font-weight:bold !important;
        cursor: pointer;
        font-size: 1rem;
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
            'title' => trans('main.'.$data->designElems['mainData']['name']),
            'url' => \URL::to('/'.$data->designElems['mainData']['url'])
        ],
        [
            'title' => $data->designElems['mainData']['title'],
            'url' => \URL::current()
        ],
    ]
])
@endsection
{{-- Content --}}
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title"><i class="{{ $data->designElems['mainData']['icon'] }}"></i> {{$data->designElems['mainData']['title']}}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="symbol symbol-45 symbol-light mr-5">
                        <span class="symbol-label">
                            <span class="svg-icon svg-icon-xl svg-icon-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"></path>
                                        <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"></path>
                                    </g>
                                </svg>
                            </span>
                        </span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                        <a href="#" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder">{{$data->data->subject}}</a>
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
                                <span class="text-muted font-weight-bold">{{date('d M y H:i A',strtotime($data->data->created_at))}}</span>
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
                                <span class="text-muted font-weight-bold">{{$data->data->department}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pt-3">
                    <p class="text-dark-75 font-size-lg font-weight-normal pt-5 mb-6">{{$data->data->description}}</p>
                    @if(!empty($data->data->files) && $data->data->files[0] != '')
                    <div class="bgi-no-repeat bgi-size-cover rounded min-h-295px" style="background-image: url({{$data->data->files[0]->photo}})"></div>
                    @endif
                    <div class="pt-6">
                        <a href="#" class="btn btn-light-primary btn-sm rounded font-weight-bolder font-size-sm p-2">
                        <span class="svg-icon svg-icon-md pr-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"></path>
                                    <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"></path>
                                </g>
                            </svg>
                        </span>{{$data->commentsCount}} {{trans('main.comments')}}</a>
                    </div>
                    @foreach($data->comments as $comment)
                    <div class="d-flex pt-5">
                        <div class="symbol symbol-40 {{$comment->image != '' ? "" : 'symbol-light-success'}}  mr-5 mt-1">
                            <span class="symbol-label">
                                @if($comment->image)
                                <img src="{{$comment->image}}" class="h-75 align-self-end" alt="">
                                @endif
                            </span>
                        </div>
                        <div class="d-flex flex-column flex-row-fluid">
                            <div class="d-flex align-items-center flex-wrap">
                                <a href="#" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder pr-6">{{$comment->name}}</a>
                                <span class="text-muted font-weight-normal flex-grow-1 font-size-sm">{{ date('d M y H:i A',strtotime($comment->created_at)) }}</span>
                                <span class="text-muted mr-1 ml-1 actions reply" data-area="{{$comment->id}}">{{trans('main.reply')}}</span>
                                <span class="text-muted mr-1 ml-1 actions" onclick="deleteComment({{$comment->id}})">{{trans('main.delete')}}</span>
                            </div>
                            <span class="text-dark-75 font-size-sm font-weight-normal pt-1">{{$comment->comment}}</span>
                        </div>
                    </div>
                    @if(count($comment->replies))
                    <div class="row mx-10">
                        @foreach($comment->replies as $reply)
                        <div class="d-flex w-100 pt-5">
                            <div class="symbol symbol-40 {{$reply->image != '' ? "" : 'symbol-light-success'}}  mr-5 mt-1">
                                <span class="symbol-label">
                                    @if($reply->image)
                                    <img src="{{$reply->image}}" class="h-75 align-self-end" alt="">
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex flex-column flex-row-fluid">
                                <div class="d-flex align-items-center flex-wrap">
                                    <a href="#" class="text-dark-75 text-hover-primary mb-1 font-size-lg font-weight-bolder pr-6">{{$reply->name}}</a>
                                    <span class="text-muted font-weight-normal flex-grow-1 font-size-sm">{{ date('d M y H:i A',strtotime($reply->created_at)) }}</span>
                                    <span class="text-muted mr-1 ml-1 actions" onclick="deleteComment({{$reply->id}})">{{trans('main.delete')}}</span>
                                </div>
                                <span class="text-dark-75 font-size-sm font-weight-normal pt-1">{{$reply->comment}}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="separator separator-solid mt-9 mb-4"></div>
                <form class="position-relative">
                    <textarea id="kt_forms_widget_11_input" class="form-control border-0 p-0 pr-10 resize-none comment" rows="1"  name="comment" placeholder="{{ trans('main.comment') }}..."  style="overflow: hidden; overflow-wrap: break-word; height: 20px;"></textarea>
                    <div class="position-absolute top-0 right-0 mt-n1 mr-n2">
                        <span class="btn btn-icon btn-sm btn-hover-icon-primary">
                            <i class="flaticon2-clip-symbol icon-ms attach"></i>
                        </span>
                        <span class="btn btn-icon btn-sm btn-hover-icon-primary">
                            <i class="flaticon2-send-1 icon-ms newComm" data-area="0"></i>
                        </span>
                    </div>
                    <div class="dropzone dropzone-default" id="commentFile">
                        <div class="dropzone-msg dz-message needsclick">
                            <i class="flaticon-upload"></i>
                            <h3 class="dropzone-msg-title">{{ trans('main.attachFiles') }}</h3>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom mb-5">
            <div class="card-header">
                <h3 class="card-title"> {{trans('main.info')}}</h3>
            </div>
            <div class="card-body">
                <div class="details">
                    <ul class="list-unstyled">
                        <li class="font-weight-bold text-dark-50">{{ trans('main.client') }} <span class="float-right">{{ $data->data->client }}</span></li>
                        <div class="clearfix"></div>
                        <li class="font-weight-bold text-dark-50">{{ trans('main.department') }} <span class="float-right">{{ $data->data->department }}</span></li>
                        <div class="clearfix"></div>
                        <li class="font-weight-bold text-dark-50">{{ trans('main.status') }} <span class="float-right">{{ $data->data->statusText }}</span></li>
                        <div class="clearfix"></div>
                        <li class="font-weight-bold text-dark-50">{{ trans('main.date') }} <span class="float-right">{{ date('d M y H:i A',strtotime($data->data->created_at)) }}</span></li>
                        <div class="clearfix"></div>
                        <li class="font-weight-bold text-dark-50">{{ trans('main.lastReply') }} <span class="float-right">{{ !empty($data->comments) ? $data->comments[0]->created_at : date('d M y H:i A',strtotime($data->data->created_at)) }}</span></li>
                        <div class="clearfix"></div>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title"> {{trans('main.attachments')}}</h3>
            </div>
            <div class="card-body">
                <div class="attachments">
                    <div class="uploads">
                        @foreach($data->data->files as $oneFile)
                        <div class="m-2 border text-center">
                            <a href="{{ $oneFile->photo }}" target="_blank">
                                @if($oneFile->file_type == 'photo')
                                <img class="w-100 mb-0" src="{{ $oneFile->photo }}" alt="attachment">
                                @elseif($oneFile->file_type == 'video')
                                <video src="{{ $oneFile->photo }}" controls>
                                    <source src="{{ $oneFile->photo }}" type="video/mp4">
                                </video>
                                @endif
                            </a>
                            <h6 class="mb-0 p-3 bg-gray-100"> 
                                {{ $oneFile->photo_name }} <br>
                                <small class="text-muted">{{ $oneFile->photo_size }}</small>
                            </h6>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts') 
<script src="{{ asset('assets/tenant/components/comments.js') }}"></script>      
@endsection
