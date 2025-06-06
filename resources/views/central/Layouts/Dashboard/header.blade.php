<div class="main-header sticky side-header nav nav-item">
    <div class="container-fluid">
        <div class="main-header-left">
            <div class="responsive-logo">
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/logo.png') }}" class="logo-1 logo-color1" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/logo2.png') }}" class="logo-1 logo-color2" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/logo3.png') }}" class="logo-1 logo-color3" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/logo4.png') }}" class="logo-1 logo-color4" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/logo5.png') }}" class="logo-1 logo-color5" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/logo6.png') }}" class="logo-1 logo-color6" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/logo-white.png') }}" class="dark-logo-1" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/favicon.png') }}" class="logo-2 logo-color1" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/favicon2.png') }}" class="logo-2 logo-color2" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/favicon3.png') }}" class="logo-2 logo-color3" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/favicon4.png') }}" class="logo-2 logo-color4" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/favicon5.png') }}" class="logo-2 logo-color5" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/favicon6.png') }}" class="logo-2 logo-color6" alt="logo"></a>
                <a href="index.html"><img src="{{ asset('assets/dashboard/assets/img/brand/favicon-white.png') }}" class="dark-logo-2" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle d-md-none" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
        </div>
        <div class="main-header-right">
            <div class="nav nav-item  navbar-nav-right ml-auto">
                <div class="nav-link" id="bs-example-navbar-collapse-1">
                    <form class="navbar-form" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button type="reset" class="btn btn-default">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button type="submit" class="btn btn-default nav-link resp-btn">
                                    <i class="fe fe-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="dropdown nav-item main-header-notification langs-area"> 
                    <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" role="button">
                        @if(DIRECTION == 'ltr')
                        <div class="avatar rounded-circle user-langs lang-item" data-next-area="ar">ع</div>
                        @else
                        <div class="avatar rounded-circle user-langs lang-item en" data-next-area="en">En</div>
                        @endif
                    </a>
                </div>
                <div class="darkModeContainer">
                    <div id="day"></div>
                    <div id="night"></div>
                    <svg version="1.1" id="darkmode" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="369px" height="171.667px" viewBox="0 0 369 171.667" enable-background="new 0 0 369 171.667" xml:space="preserve">
                        <g>
                            <polygon class="star" id="star-1" fill="#A2B5BF" points="166.253,132.982 164.364,135.676 160.983,136.488 163.196,138.965 162.996,142.16 166.253,140.998 169.509,142.16 169.309,138.965 171.522,136.488 168.142,135.676  "/>
                            <polygon class="star" id="star-2" fill="#A2B5BF" points="175.522,44.243 172.684,48.29 167.603,49.51 170.929,53.233 170.628,58.035 
                                175.522,56.288 180.417,58.035 180.116,53.233 183.442,49.51 178.361,48.29    "/>
                            <polygon class="star" id="star-3" fill="#A2B5BF" points="208.22,91.845 206.083,94.891 202.259,95.81 204.763,98.61 204.535,102.226 
                                208.22,100.911 211.903,102.226 211.677,98.61 214.181,95.81 210.356,94.891   "/>
                            <polygon class="star" id="star-4" fill="#A2B5BF" points="252.545,39.052 250.409,42.098 246.585,43.017 249.089,45.819 248.86,49.433 
                                252.545,48.118 256.229,49.433 256.002,45.819 258.506,43.017 254.682,42.098  "/>
                            <polygon class="star" id="star-5" fill="#A2B5BF" points="280.151,84.949 282.749,88.997 287.401,90.217 284.355,93.94 284.632,98.742 
                                280.151,96.995 275.669,98.742 275.946,93.94 272.899,90.217 277.552,88.997   "/>
                            <polygon class="star" id="star-6" fill="#A2B5BF" points="249.791,124.466 246.668,128.919 241.076,130.261 244.737,134.356 244.405,139.64 
                                249.791,137.718 255.178,139.64 254.845,134.356 258.506,130.261 252.914,128.919  "/>
                            <g id="moon">
                                <g>
                                    <g>
                                        <path fill="#CAD9DD" d="M255.662,153.639c-18.114,0-35.144-7.055-47.952-19.863c-12.808-12.807-19.861-29.837-19.861-47.951
                                            s7.054-35.144,19.861-47.951c12.809-12.809,29.838-19.862,47.952-19.862s35.144,7.054,47.951,19.862
                                            c12.809,12.808,19.862,29.838,19.862,47.951s-7.054,35.144-19.862,47.951C290.806,146.584,273.776,153.639,255.662,153.639z"/>
                                        <path fill="#A2B5BF" d="M255.662,21.672c35.431,0,62.713,28.731,62.713,64.162c0,35.431-27.282,62.167-62.713,62.167
                                            s-64.153-26.744-64.153-62.175C191.509,50.394,220.231,21.672,255.662,21.672 M255.662,14.35c-9.646,0-19.007,1.891-27.823,5.62
                                            c-8.512,3.6-16.155,8.753-22.717,15.315c-6.563,6.562-11.715,14.204-15.314,22.717c-3.729,8.816-5.62,18.178-5.62,27.823
                                            s1.891,19.007,5.62,27.824c3.6,8.512,8.752,16.154,15.314,22.717c6.562,6.561,14.205,11.713,22.717,15.314
                                            c8.816,3.729,18.178,5.619,27.823,5.619s19.007-1.891,27.823-5.619c8.512-3.602,16.154-8.754,22.717-15.314
                                            c6.562-6.563,11.714-14.205,15.314-22.717c3.729-8.816,5.619-18.178,5.619-27.824s-1.891-19.007-5.619-27.823
                                            c-3.601-8.513-8.753-16.155-15.314-22.717c-6.563-6.562-14.205-11.715-22.717-15.315
                                            C274.669,16.241,265.308,14.35,255.662,14.35L255.662,14.35z"/>
                                    </g>
                                </g>
                                <path fill="#A2B5BF" d="M295.264,35.35c8.768,10.972,14.013,24.881,14.013,40.017c0,35.43-28.723,64.153-64.153,64.153
                                    c-14.944,0-28.696-5.109-39.602-13.68c11.755,14.711,29.846,24.137,50.141,24.137c35.431,0,64.153-28.721,64.153-64.152
                                    C319.815,65.339,310.213,47.096,295.264,35.35z"/>
                                <circle fill="#CAD9DD" cx="304.291" cy="98.701" r="5.392"/>
                                <path fill="#CAD9DD" d="M278.364,126.115c0-8.791-7.127-15.916-15.918-15.916s-15.918,7.125-15.918,15.916
                                    c0,8.793,7.127,15.92,15.918,15.92S278.364,134.908,278.364,126.115z"/>
                                <circle fill="#CAD9DD" cx="242.372" cy="138.547" r="3.603"/>
                                <circle fill="#CAD9DD" cx="306.504" cy="57.724" r="3.456"/>
                                <g>
                                    <circle fill="#A2B5BF" cx="262.446" cy="126.115" r="14.346"/>
                                    <path opacity="0.12" fill="#231F20" d="M249.772,128.002c0-7.922,6.422-14.346,14.346-14.346c3.824,0,7.297,1.5,9.869,3.941
                                        c-2.613-3.535-6.809-5.826-11.541-5.826c-7.923,0-14.346,6.422-14.346,14.344c0,4.1,1.721,7.793,4.477,10.406
                                        C250.815,134.139,249.772,131.193,249.772,128.002z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="239.621" cy="65.064" r="9.908"/>
                                    <path opacity="0.12" fill="#231F20" d="M230.867,66.366c0-5.472,4.437-9.907,9.908-9.907c2.642,0,5.04,1.036,6.816,2.721
                                        c-1.805-2.44-4.702-4.023-7.972-4.023c-5.472,0-9.907,4.436-9.907,9.908c0,2.829,1.188,5.381,3.091,7.187
                                        C231.587,70.604,230.867,68.57,230.867,66.366z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="292.418" cy="64.686" r="7.339"/>
                                    <path opacity="0.12" fill="#231F20" d="M285.934,65.651c0-4.054,3.286-7.34,7.339-7.34c1.957,0,3.734,0.768,5.05,2.016
                                        c-1.337-1.808-3.483-2.98-5.904-2.98c-4.054,0-7.339,3.286-7.339,7.34c0,2.096,0.88,3.985,2.289,5.323
                                        C286.468,68.791,285.934,67.283,285.934,65.651z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="285.796" cy="108.111" r="3.48"/>
                                    <path opacity="0.12" fill="#231F20" d="M282.721,108.568c0-1.922,1.559-3.48,3.481-3.48c0.928,0,1.77,0.365,2.395,0.957
                                        c-0.635-0.857-1.652-1.414-2.801-1.414c-1.922,0-3.48,1.559-3.48,3.48c0,0.994,0.418,1.891,1.086,2.525
                                        C282.974,110.059,282.721,109.344,282.721,108.568z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="222.605" cy="77.949" r="4.441"/>
                                    <path opacity="0.12" fill="#231F20" d="M218.682,78.533c0-2.452,1.988-4.44,4.44-4.44c1.185,0,2.26,0.464,3.055,1.22
                                        c-0.809-1.094-2.107-1.804-3.572-1.804c-2.452,0-4.44,1.988-4.44,4.44c0,1.269,0.532,2.412,1.386,3.222
                                        C219.005,80.433,218.682,79.521,218.682,78.533z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="304.291" cy="98.701" r="4.441"/>
                                    <path opacity="0.12" fill="#231F20" d="M300.367,99.284c0-2.452,1.987-4.44,4.44-4.44c1.184,0,2.26,0.465,3.055,1.22
                                        c-0.809-1.094-2.107-1.804-3.572-1.804c-2.452,0-4.44,1.988-4.44,4.441c0,1.268,0.532,2.411,1.386,3.221
                                        C300.69,101.184,300.367,100.272,300.367,99.284z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="242.372" cy="138.547" r="2.751"/>
                                    <path opacity="0.12" fill="#231F20" d="M239.941,138.908c0-1.52,1.231-2.75,2.751-2.75c0.734,0,1.4,0.287,1.893,0.756
                                        c-0.5-0.68-1.305-1.119-2.213-1.119c-1.52,0-2.752,1.232-2.752,2.752c0,0.785,0.33,1.494,0.859,1.996
                                        C240.142,140.086,239.941,139.521,239.941,138.908z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="238.652" cy="40.059" r="2.751"/>
                                    <path opacity="0.12" fill="#231F20" d="M236.222,40.421c0-1.52,1.231-2.752,2.751-2.752c0.733,0,1.399,0.288,1.894,0.756
                                        c-0.502-0.678-1.307-1.117-2.214-1.117c-1.52,0-2.751,1.231-2.751,2.751c0,0.786,0.33,1.494,0.858,1.996
                                        C236.421,41.598,236.222,41.033,236.222,40.421z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="252.921" cy="44.149" r="5.33"/>
                                    <path opacity="0.12" fill="#231F20" d="M248.213,44.85c0-2.943,2.386-5.33,5.329-5.33c1.422,0,2.712,0.558,3.668,1.464
                                        c-0.971-1.313-2.53-2.164-4.289-2.164c-2.943,0-5.329,2.386-5.329,5.329c0,1.522,0.64,2.895,1.663,3.866
                                        C248.601,47.13,248.213,46.036,248.213,44.85z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="258.251" cy="81.17" r="4.195"/>
                                    <path opacity="0.12" fill="#231F20" d="M254.546,81.722c0-2.317,1.877-4.195,4.193-4.195c1.119,0,2.135,0.438,2.887,1.152
                                        c-0.764-1.033-1.991-1.704-3.375-1.704c-2.316,0-4.194,1.878-4.194,4.195c0,1.198,0.503,2.278,1.309,3.042
                                        C254.851,83.516,254.546,82.655,254.546,81.722z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="306.504" cy="57.724" r="2.751"/>
                                    <path opacity="0.12" fill="#231F20" d="M304.073,58.085c0-1.519,1.231-2.751,2.751-2.751c0.733,0,1.399,0.288,1.894,0.756
                                        c-0.502-0.678-1.307-1.117-2.215-1.117c-1.519,0-2.75,1.231-2.75,2.751c0,0.786,0.33,1.494,0.857,1.995
                                        C304.272,59.263,304.073,58.698,304.073,58.085z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="207.412" cy="62.199" r="2.751"/>
                                    <path opacity="0.12" fill="#231F20" d="M204.981,62.56c0-1.52,1.232-2.751,2.752-2.751c0.733,0,1.398,0.287,1.893,0.755
                                        c-0.502-0.678-1.307-1.117-2.213-1.117c-1.52,0-2.752,1.232-2.752,2.752c0,0.785,0.33,1.494,0.858,1.995
                                        C205.182,63.737,204.981,63.171,204.981,62.56z"/>
                                </g>
                                <g>
                                    <circle fill="#A2B5BF" cx="214.279" cy="103.596" r="2.751"/>
                                    <path opacity="0.12" fill="#231F20" d="M211.848,103.958c0-1.52,1.231-2.751,2.751-2.751c0.734,0,1.4,0.287,1.893,0.755
                                        c-0.5-0.678-1.305-1.117-2.213-1.117c-1.52,0-2.751,1.232-2.751,2.752c0,0.785,0.33,1.494,0.858,1.996
                                        C212.048,105.135,211.848,104.568,211.848,103.958z"/>
                                </g>
                            </g>
                        </g>
                        <g>
                            <g id="sun">
                                <g>
                                    <g>
                                        <path fill="#F4E962" d="M255.661,153.638c-18.113,0-35.144-7.054-47.951-19.862c-12.809-12.808-19.862-29.838-19.862-47.951
                                            s7.054-35.144,19.862-47.951c12.808-12.809,29.838-19.862,47.951-19.862c18.114,0,35.144,7.054,47.952,19.862
                                            c12.808,12.808,19.861,29.838,19.861,47.951s-7.054,35.144-19.861,47.951C290.805,146.584,273.775,153.638,255.661,153.638z"/>
                                        <path fill="#F9C941" d="M255.661,21.671c35.431,0,64.153,28.722,64.153,64.153s-28.723,64.153-64.153,64.153
                                            s-64.153-28.723-64.153-64.153S220.23,21.671,255.661,21.671 M255.661,14.35c-9.646,0-19.007,1.891-27.823,5.62
                                            c-8.512,3.601-16.154,8.753-22.717,15.314c-6.562,6.562-11.714,14.205-15.314,22.717c-3.729,8.816-5.62,18.178-5.62,27.823
                                            s1.891,19.007,5.62,27.823c3.601,8.512,8.753,16.155,15.314,22.717c6.563,6.562,14.205,11.714,22.717,15.314
                                            c8.816,3.729,18.178,5.62,27.823,5.62s19.007-1.891,27.823-5.62c8.512-3.601,16.155-8.753,22.717-15.314
                                            c6.563-6.562,11.715-14.205,15.314-22.717c3.729-8.816,5.62-18.178,5.62-27.823s-1.891-19.007-5.62-27.823
                                            c-3.6-8.512-8.752-16.155-15.314-22.717c-6.562-6.562-14.205-11.714-22.717-15.314C274.668,16.241,265.307,14.35,255.661,14.35
                                            L255.661,14.35z"/>
                                    </g>
                                </g>
                            </g>
                            <path id="cloud" fill="#ECF0F1" stroke="#CAD4D8" stroke-width="6" stroke-miterlimit="10" d="M153.269,109.614h2.813
                                c-1.348-2.84-2.124-6.003-2.124-9.354c0-12.083,9.794-21.878,21.877-21.878c7.872,0,14.751,4.172,18.605,10.411
                                c2.121-1.246,4.583-1.974,7.221-1.974c7.889,0,14.285,6.396,14.285,14.285c0,2.1-0.465,4.087-1.277,5.882h6.354
                                c6.604,0,12.007,5.403,12.007,12.007s-5.403,12.006-12.007,12.006h-25.151H179.48h-26.212c-5.881,0-10.692-4.812-10.692-10.692
                                S147.388,109.614,153.269,109.614z"/>
                        </g>
                    </svg>          
                </div>
                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    @php
                    $image = \App\Models\CentralUser::getData(\App\Models\CentralUser::getOne(USER_ID))->photo;
                    @endphp
                    <a class="profile-user d-flex" href=""><img alt="" src="{{ $image }}">
                        <div class="p-text d-none">
                            <span class="p-name font-weight-bold">{{ FULL_NAME }}</span>
                            <small class="p-sub-text">{{ GROUP_NAME }}</small>
                        </div>
                    </a>
                    <div class="dropdown-menu shadow">
                        <div class="main-header-profile header-img">
                            <div class="main-img-user"><img alt="" src="{{ $image }}"></div>
                            <h6>{{ FULL_NAME }}</h6><span>{{ GROUP_NAME }}</span>
                        </div>
                        <a class="dropdown-item" href="{{ URL::to('/invoices') }}"><i class="fas fa-file-alt"></i> {{ trans('main.subs_invoices') }}</a>
                        <a class="dropdown-item" href="{{ URL::to('/logout') }}"><i class="fas fa-sign-out-alt"></i> {{ trans('main.logout') }}</a>
                    </div>
                </div>
                <div class="dropdown main-header-message right-toggle" style="visibility: hidden;">
                    <a class="nav-link pr-0" data-toggle="sidebar-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}" data-target=".sidebar-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}">
                        <i class="ion ion-md-menu tx-20 bg-transparent"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /main-header