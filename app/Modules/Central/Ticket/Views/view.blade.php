{{-- Extends layout --}}
@extends('central.Layouts.Dashboard.master')
@section('title',$data->designElems['mainData']['title'])

@section('styles')
<style type="text/css" media="screen">
    .col-xl-8 .rounded-circle{
        width: 50px;
        height: 50px;
    }
    .col-xl-8 .media .media-body{
        margin-top: 15px;
    }
    .media-body p a{
        color: inherit;
    }
    .dropdown-item{
        cursor: pointer;
    }
</style>
@endsection
@section('content')
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-11">
            <div class="page-title-box">
            </div>
        </div>
    </div>     

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card d-block">
                <div class="card-header border-bottom">
                    <h3 class="header-title w-50 float-left"><i class="ti-receipt"></i> {{ trans('main.ticket') }} #{{ $data->data->id }}</h3>
                    <div class="card-toolbar w-50 float-left text-right">
                        <p class="btn btn-md btn-link mb-0 mt-2" dir="ltr"> <i class="fa fa-calendar"></i> {{ date('d M y',strtotime($data->data->created_at)) }} <small class="text-muted">{{ date('H:i A',strtotime($data->data->created_at)) }}</small></p>
                    </div>    
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div class="clerfix"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="mt-2 mb-1">{{ trans('main.client') }} :</label>
                            <div class="media">
                                <img src="{{ $data->data->client_image }}" alt="Arya S"
                                    class="rounded-circle mr-2" height="24" />
                                <div class="media-body mt-3 mx-2">
                                    <p><a href="{{ URL::to('/clients/view/'.$data->data->user_id) }}" target="_blank"> {{ $data->data->client }}</a> </p>
                                </div>
                            </div>
                        </div>
                        @if(!empty($data->data->assignment))
                        <div class="col-md-6">
                            <label class="mt-2 mb-1">{{ trans('main.assignment') }} :</label>
                            @foreach($data->data->assignment as $user)
                            @php 
                            $assignedUser = \App\Models\CentralUser::getOne($user);
                            $assignedUser = \App\Models\CentralUser::getData($assignedUser);
                            @endphp
                            <div class="media mb-2">
                                <img src="{{ $assignedUser->photo }}" alt="{{ $assignedUser->id }}"
                                    class="rounded-circle mr-2" height="24" />
                                <div class="media-body">
                                    <p> {{ $assignedUser->name }} </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="row my-3">
                        <div class="col-md-4">
                            <label class="mt-2 mb-1">{{ trans('main.department') }} :</label>
                            <p><i class='mdi mdi-ticket font-18 text-success mr-1 align-middle'></i> {{ $data->data->department }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1">{{ trans('main.status') }} :</label>
                            <div class="form-row">
                                <p>{{ $data->data->statusText }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="mt-2 mb-1">{{ trans('main.priority') }} :</label>
                            <div class="form-row">
                                <p>{{ $data->data->priority }}</p>
                            </div>
                        </div>
                    </div>
                    <h4 class="mb-3 mt-0 font-18">{{ ucfirst($data->data->subject) }}</h4>
                    <p class="text-muted mb-0">
                        {!! $data->data->description !!}
                    </p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-4 mt-0 font-16">{{ trans('main.comments') }} ({{ $data->commentsCount }})</h4>
                    <div class="clerfix"></div>
                    @foreach($data->comments as $comment)
                    <div class="card-box" style="margin-bottom: 0;border-bottom: 1px solid #eee;" id="tableRaw{{ $comment->id }}">
                        <div class="media my-3">
                            <img class="mr-2 avatar-sm rounded-circle" src="{{ $comment->image }}" alt="Generic placeholder image">
                            <div class="media-body mt-0 mx-2">
                                <div class="dropdown float-right text-muted">
                                    @if(\Helper::checkRules('deleteComment-'.$data->designElems['mainData']['nameOne']) || $comment->created_by == USER_ID)
                                    <a onclick="deleteComment({{ $comment->id }})" class="dropdown-item">{{ trans('main.delete') }}</a>
                                    @endif
                                </div>
                                <h5 class="my-0"><a href="contacts-profile.html" class="text-reset">{{$comment->creator}}</a></h5>
                                <p class="text-muted mb-1"><small>{{ $comment->created_at }}</small></p>
                                <div class="font-16 font-italic text-dark mb-1">
                                    {!! $comment->comment !!}
                                </div>
                                @if(\Helper::checkRules('addComment-'.$data->designElems['mainData']['nameOne']))
                                <a data-area="{{ $comment->id }}" class="text-muted reply font-13 d-inline-block"><i class="mdi mdi-reply"></i> Reply</a>
                                @endif
                            </div>
                        </div>
                        @if(!empty($comment->replies))
                        <div class="post-user-comment-box mt-2 mr-2 ml-2">
                            @foreach($comment->replies as $reply)
                            <div class="media my-3">
                                <img class="mr-2 avatar-sm rounded-circle" src="{{ $reply->image }}" alt="Generic placeholder image">
                                <div class="media-body mt-0 mx-2">
                                    <h5 class="my-0">
                                        <a href="contacts-profile.html" class="text-reset">{{ $reply->creator }}</a> 
                                        <br>
                                        <small class="text-muted">{{ $reply->created_at }}</small>
                                    </h5>
                                    {!! $reply->comment !!}
                                    <br>
                                    @if(\Helper::checkRules('addComment-'.$data->designElems['mainData']['nameOne']))
                                    <a data-area="{{ $reply->id }}" class="text-muted reply font-13 d-inline-block"><i class="mdi mdi-reply"></i> Reply</a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                    <div class="border rounded">
                        <form action="#" class="comment-area-box">
                            <textarea rows="3" class="form-control comment border-0 resize-none" name="comment" placeholder="{{ trans('main.comment') }}..."></textarea>
                            <div class="p-2 bg-light d-flex justify-content-between align-items-center">
                                <div></div>
                                <button type="submit" data-area="0" class="btn newComm btn-sm btn-success"><i class='fab fa-telegram-plane'></i> {{ trans('main.comment') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title font-16 mb-3">{{ trans('main.attachments') }}</h5>
                    @foreach($data->data->files as $oneFile)
                    <div class="card mb-1 shadow-none border">
                        <div class="p-2">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="avatar-sm">
                                        <img class="avatar-title badge-soft-primary rounded" src="{{ asset('assets/tenant/media/svg/files/'.(explode('.', $oneFile->photo_name)[1] == 'png' ? 'jpg' : explode('.', $oneFile->photo_name)[1]).'.svg') }}" alt="">
                                    </div>
                                </div>
                                <div class="col pl-0">
                                    <a href="{{asset('/uploads/tickets/'.$data->data->id.'/'.$oneFile->photo_name)}}" class="text-muted font-weight-bold" target="_blank">{{ $oneFile->photo_name }}</a>
                                    <p class="mb-0 font-12">{{ $oneFile->photo_size }}</p>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ $oneFile->photo }}" target="_blank" class="btn btn-link font-16 text-muted">
                                        <i class="dripicons-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')
@include('central.Partials.photoswipe_modal')
@endsection

@section('scripts') 
<script src="{{ asset('assets/dashboard/assets/components/comments.js') }}"></script>      
@endsection
