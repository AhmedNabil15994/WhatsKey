<div>
    <div class="w-100 d-block">
        <div class="text-left col-md-4 d-inline-block float-left">
            <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md d-lg-none" id="kt_app_chat_toggle">
                <span class="svg-icon svg-icon-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M18,2 L20,2 C21.6568542,2 23,3.34314575 23,5 L23,19 C23,20.6568542 21.6568542,22 20,22 L18,22 L18,2 Z" fill="#000000" opacity="0.3" />
                            <path d="M5,2 L17,2 C18.6568542,2 20,3.34314575 20,5 L20,19 C20,20.6568542 18.6568542,22 17,22 L5,22 C4.44771525,22 4,21.5522847 4,21 L4,3 C4,2.44771525 4.44771525,2 5,2 Z M12,11 C13.1045695,11 14,10.1045695 14,9 C14,7.8954305 13.1045695,7 12,7 C10.8954305,7 10,7.8954305 10,9 C10,10.1045695 10.8954305,11 12,11 Z M7.00036205,16.4995035 C6.98863236,16.6619875 7.26484009,17 7.4041679,17 C11.463736,17 14.5228466,17 16.5815,17 C16.9988413,17 17.0053266,16.6221713 16.9988413,16.5 C16.8360465,13.4332455 14.6506758,12 11.9907452,12 C9.36772908,12 7.21569918,13.5165724 7.00036205,16.4995035 Z" fill="#000000" />
                        </g>
                    </svg>
                </span>
            </button>
            <div class="dropdown dropdown-inline mt-2">
                <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ki ki-bold-more-hor icon-md"></i>
                </button>
                <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-sm">
                    <ul class="navi navi-hover">
                        <li class="navi-item newMessageItem">
                            <a href="#" class="navi-link p-2">
                                <span class="text-dark w-100 d-block text-right">
                                    New Message
                                    <i class="la la-envelope-open icon-xl"></i>
                                </span>
                            </a>
                        </li>
                        <li class="navi-item newGroupItem">
                            <a href="#" class="navi-link p-2">
                                <span class="text-dark w-100 d-block text-right">
                                    New Group
                                    <i class="la la-users icon-xl"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center col-md-4 d-inline-block float-left">
            <div class="text-dark-75 font-weight-bold font-size-h5 pt-1" dir="ltr">{{$name}}</div>
            <div style="margin-top: -5px;">
                <span class="label label-sm label-dot label-success"></span>
                <span class="font-weight-bold text-muted font-size-sm">Active</span>
            </div>
        </div>
        <div class="text-right col-md-4 d-inline-block float-left">
            <div class="dropdown dropdown-inline">
                <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md contactDetails">
                    <span class="fa-icon fa-icon-lg mt-3">
                        <i class="la la-user-circle-o icon-2x"></i>
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>