<div>
    @if($selected)
    <div class="w-300px w-xl-400px contactInfo ml-lg-8">
        <div class="card card-custom" style="height:100%">
            <div class="card-header p-0 p-5">
                <h4 class="card-title"><i class="la la-user-circle-o icon-2x"></i> {{trans('main.contactInfo')}}</h4>
                <div class="card-toolbar">
                    <button class="btn btn-icon btn-xs close">
                        <i class="la la-times icon-lg"></i>
                    </button>
                </div>
            </div>
            <div class="card-body bg-gray-100 p-0">
                <div class="scroll scroll-pull scroll-pulld" data-scroll="true" data-wheel-propagation="true" style="height: 775px">
                    <div class="text-center bg-white mb-3 mt-3 p-5">
                        <div class="symbol symbol-60 symbol-circle symbol-xl-90">
                            <div class="symbol-label" style="background-image:url('{{$chat['image']}}')"></div>
                        </div>
                        <h4 class="font-weight-bold my-2">{{$chat['name']}}</h4>
                        <span class="label label-inline font-weight-bold label-lg" dir="ltr">{{$chat['reformedPhone']}}</span>
                        @foreach($chat['labelsArr'] as $labelObj)
                        <span class="catLabel text-dark mt-2 d-block fa-icon" dir="ltr"> 
                            <i class="icon-md fas fa-tag label-cat{{$labelObj['color_id']}}"></i>
                            {{$labelObj['name_ar']}}
                        </span>
                        @endforeach
                    </div>

                    <div class="text-left bg-white mb-3 mt-3 p-5">
                        <h4 class="card-title"> <i class="la la-vcard-o icon-lg"></i>  {{trans('main.personalInfo')}}</h4>
                        @if(!str_contains($chat['id'], '@g.us'))
                        <div class="form-group mb-5 textWrap">
                            <label>{{trans('main.name')}}</label>
                            <input type="text" class="form-control" name="name" value="{{$chat['contact_details']['name']}}">
                            <i class="la la-smile icon-xl emoji-icon"></i>
                            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                        </div>
                        @endif
                        <div class="form-group mb-5">
                            <label>{{trans('main.phone')}}</label>
                            <input type="text" class="form-control text-left" dir="ltr" name="phone" value="{{$chat['contact_details']['phone']}}" readonly>
                        </div>
                        <div class="form-group mb-5">
                            <label>{{trans('main.email')}}</label>
                            <input type="text" class="form-control" name="email" value="{{$chat['contact_details']['email']}}">
                        </div>
                        <div class="form-group mb-5">
                            <label>{{trans('main.country')}}</label>
                            <input type="text" class="form-control" name="country" value="{{$chat['contact_details']['country']}}">
                        </div>
                        <div class="form-group mb-5">
                            <label>{{trans('main.city')}}</label>
                            <input type="text" class="form-control" name="city" value="{{$chat['contact_details']['city']}}">
                        </div>
                        <div class="form-group mb-5 textWrap">
                            <label>{{trans('main.extraInfo')}}</label>
                            <textarea class="form-control" name="notes">{{$chat['contact_details']['notes']}}</textarea>
                            <i class="la la-smile icon-xl emoji-icon"></i>
                            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                        </div>
                        <div class="form-group mb-5">
                            <label>{{trans('main.mods')}}</label>
                            <select name="mods[]" data-toggle="select2" class="form-control" multiple>
                                @foreach($mods as $mod)
                                @php $mod = (array) $mod; @endphp
                                <option value="{{$mod['id']}}" {{in_array($mod['id'],$chat['modsArr']) ? 'selected' : ''}}>{{$mod['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row mb-5">
                            <label class="col-9 col-form-label">{{trans('main.disableRead')}}</label>
                            <div class="col-3">
                                <span class="switch switch-outline switch-icon switch-success text-right d-block">
                                    <label>
                                        <input type="checkbox" {{$chat['disable_read'] ? 'checked' : ''}} name="disable_read"/>
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="form-group row mb-5">
                            <label class="col-8 col-form-label">{{trans('main.chatBackground')}}</label>
                            <div class="image-input image-input-empty image-input-outline" id="kt_image_5" style="background-image: url({{ $chat['background'] ? $chat['background'] : asset('assets/tenant/images/bg-chat.png')  }})">
                                <div class="image-input-wrapper"></div>
                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change Chat Background">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg"/>
                                    <input type="hidden" name="profile_avatar_remove"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel"><i class="ki ki-bold-close icon-xs text-muted"></i></span>
                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="remove" data-toggle="tooltip" title="Remove Background"><i class="ki ki-bold-close icon-xs text-muted"></i></span>
                            </div>
                        </div>
                        <div class="w-100 text-right">
                            <button type="button" class="btn btn-primary mr-2 updateDetails">{{trans('main.refresh')}}</button>
                        </div>  
                    </div>

                    @if(str_contains($chat['id'], '@g.us'))
                    <div class="text-left bg-white mb-3 mt-3 p-5">
                        <h4 class="card-title"><i class="la la-users icon-xl"></i> {{trans('main.groupParticipants')}} <span>({{count($chat['participants'])}})</span></h4>
                        @foreach($chat['participants'] as $participant)
                        <div class="d-flex align-items-center mb-10">
                            <div class="symbol symbol-40 symbol-light-white mr-5">
                                <div class="symbol-label">
                                    <img src="{{asset('assets/tenant/media/svg/avatars/004-boy-1.svg')}}" class="h-75 align-self-end" alt="">
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <a href="#" class="text-dark text-hover-primary mb-1 font-size-lg" dir="ltr">{{\App\Models\ChatDialog::reformNumber(str_replace('@s.whatsapp.net','',$participant['id']))}}</a>
                                @if($participant['admin'] == 'superadmin')
                                <span class="text-muted">Group Admin</span>
                                @elseif($participant['admin'] == 'admin')
                                <span class="text-muted">Admin</span>
                                @endif
                            </div>
                            @if($participant['admin'] != 'superadmin')
                            <div class="dropdown dropdown-inline ml-2" data-toggle="tooltip" title="" data-placement="left" data-original-title="{{trans('main.actions')}}">
                                <a href="#" class="btn btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ki ki-bold-more-hor"></i>
                                </a>
                                <div class="dropdown-menu p-0 m-0 dropdown-menu-xs dropdown-menu-right">
                                    <ul class="navi navi-hover">
                                        @if($participant['admin'] != 'superadmin')
                                        <li class="navi-item" onclick="Livewire.emit('removeGroupParticipants','{{str_replace('@s.whatsapp.net','',$participant['id'])}}')">
                                            <a href="#" class="navi-link p-2">
                                                <span class="text-dark w-100 d-block text-right">Remove</span>
                                            </a>
                                        </li>
                                        @endif
                                        @if($participant['admin'] != 'admin' && $participant['admin'] != 'superadmin')
                                        <li class="navi-item" onclick="Livewire.emit('promoteGroupParticipants','{{str_replace('@s.whatsapp.net','',$participant['id'])}}')">
                                            <a href="#" class="navi-link p-2">
                                                <span class="text-dark w-100 d-block text-right">Promote</span>
                                            </a>
                                        </li>
                                        @endif
                                        @if($participant['admin'] == 'admin')
                                        <li class="navi-item" onclick="Livewire.emit('demoteGroupParticipants','{{str_replace('@s.whatsapp.net','',$participant['id'])}}')">
                                            <a href="#" class="navi-link p-2">
                                                <span class="text-dark w-100 d-block text-right">Demote</span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            @else
                            <a href="#" class="btn btn-outline-danger btn-xs">
                                <span>
                                    <i class="flaticon-logout icon-md"></i> Leave Group 
                                </span>
                            </a>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <div class="text-left bg-white mb-3 mt-3 p-5">
                        <div class="row p-0 m-0">
                            <div class="col-8">
                                <h4 class="card-title"><i class="la la-external-link-alt icon-xl"></i> {{trans('main.groupInviteCode')}}</h4>
                            </div>                            
                            <div class="col-4 text-right">
                                <a href="#" class="btn btn-outline-success btn-xs" onclick="Livewire.emit('getInviteCode')">
                                    <span>
                                        <i class="la la-share icon-md"></i> Get Link
                                    </span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="input-group">
                            <input type="text" class="form-control" id="kt_clipboard_1" placeholder="{{trans('main.groupInviteCode')}}" value="{{$groupInviteLink}}" />
                            <div class="input-group-append">
                                <a href="#" class="btn btn-secondary" data-clipboard="true" data-clipboard-target="#kt_clipboard_1"><i class="la la-copy"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="text-left bg-white mb-3 mt-3 p-5">
                        <h4 class="card-title"><i class="la la-users icon-xl"></i> {{trans('main.addGroupParticipants')}}</h4>
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
                                @foreach($contacts as $contact)
                                <option value="{{ str_replace('+','',$contact['phone']) }}">{{ $contact['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" data-id="2" style="display: none;">
                            <label>{{trans('main.numbers')}}</label>
                            <textarea class="form-control" name="participants" placeholder="{{ trans('main.whatsappNos2') }}"></textarea>
                        </div>

                        <div class="w-100 text-right">
                            <button type="button" class="btn btn-primary mr-2 addParticipants">{{trans('main.addGroupParticipants')}}</button>
                        </div> 
                    </div>

                    <div class="text-left bg-white mb-3 mt-3 p-5">
                        <h4 class="card-title"><i class="la la-cogs icon-xl"></i> {{trans('main.groupSettings')}}</h4>
                        <div class="form-group textWrap">
                            <label>{{trans('main.groupName')}}</label>
                            <textarea class="form-control" name="groupName" placeholder="{{ trans('main.groupName') }}">{{$chat['name']}}</textarea>
                            <i class="la la-smile icon-xl emoji-icon"></i>
                            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                        </div>
                        <div class="form-group textWrap">
                            <label>{{trans('main.groupDescription')}}</label>
                            <textarea class="form-control" name="groupDescription" placeholder="{{ trans('main.groupDescription') }}">{{$chat['group_description']}}</textarea>
                            <i class="la la-smile icon-xl emoji-icon"></i>
                            <emoji-picker class="hidden" locale="en" data-source="{{asset('assets/tenant/js/data.json')}}"></emoji-picker>
                        </div>
                        <div class="form-group">
                            <label>{{trans('main.abilityToSendMessages')}}</label>
                            <select name="send_messages" class="form-control" data-toggle="select2">
                                <option value="announcement" {{ $chat['send_messages'] == "announcement" ? 'selected' : '' }}>{{trans('main.admins')}}</option>
                                <option value="not_announcement" {{ $chat['send_messages'] == "not_announcement" ? 'selected' : '' }}>{{trans('main.all')}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{trans('main.abilityToEdit')}}</label>
                            <select name="edit_info" class="form-control" data-toggle="select2">
                                <option value="locked" {{ $chat['edit_info'] == "locked" ? 'selected' : '' }}>{{trans('main.admins')}}</option>
                                <option value="unlocked" {{ $chat['edit_info'] == "unlocked" ? 'selected' : '' }}>{{trans('main.all')}}</option>
                            </select>
                        </div>
                        <div class="w-100 text-right">
                            <button type="button" class="btn btn-primary mr-2 updateSettings">{{trans('main.updateSettings')}}</button>
                        </div> 
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
