<!-- Loader -->
<div id="global-loader">
    <img src="{{ asset('assets/dashboard/assets/img/loader-2.svg') }}" class="loader-img" alt="Loader">
</div>
<!-- /Loader -->

<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="index.html">
            <img src="{{ asset('assets/dashboard/assets/img/brand/logo.png') }}" class="main-logo logo-color1" alt="logo">
            <img src="{{ asset('assets/dashboard/assets/img/brand/logo2.png') }}" class="main-logo logo-color2" alt="logo">
            <img src="{{ asset('assets/dashboard/assets/img/brand/logo3.png') }}" class="main-logo logo-color3" alt="logo">
            <img src="{{ asset('assets/dashboard/assets/img/brand/logo4.png') }}" class="main-logo logo-color4" alt="logo">
            <img src="{{ asset('assets/dashboard/assets/img/brand/logo5.png') }}" class="main-logo logo-color5" alt="logo">
            <img src="{{ asset('assets/dashboard/assets/img/brand/logo6.png') }}" class="main-logo logo-color6" alt="logo">
        </a>
        <a class="desktop-logo logo-dark active" href="{{ URL::to('/dashboard') }}"><img src="{{ asset('assets/images/noBackLogo.png') }}" class="main-logo dark-theme" alt="logo"></a>
        <div class="app-sidebar__toggle" data-toggle="sidebar">
            <a class="open-toggle" href="#"><i class="header-icon fe fe-chevron-left"></i></a>
            <a class="close-toggle" href="#"><i class="header-icon fe fe-chevron-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}"></i></a>
        </div>
    </div>
    <div class="main-sidemenu sidebar-scroll">
        <ul class="side-menu">
            {{-- <li><h3>Main</h3></li> --}}
            <li class="slide">
                <a class="side-menu__item {{ Active(URL::to('/dashboard')) }}" href="{{ URL::to('/dashboard') }}">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/dashboard.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.dashboard') }}</span>
                </a>
            </li>  

            @if(\Helper::checkRules('list-clients,list-transfers'))
               <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/team.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.clients') }}</span><i class="angle fe fe-chevron-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}"></i>
                </a>
                <ul class="slide-menu">
                    @if(\Helper::checkRules('list-clients'))
                    <li><a class="slide-item" href="{{ URL::to('/clients') }}">{{ trans('main.clients') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-transfers'))
                    <li><a class="slide-item" href="{{ URL::to('/transfers') }}">{{ trans('main.transfers') }}</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(\Helper::checkRules('list-invoices'))
            <li class="slide">
                <a class="side-menu__item {{ Active(URL::to('/invoices')) }}" href="{{ URL::to('/invoices') }}">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/invoice.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.invoices') }}</span>
                </a>
            </li> 
            @endif

            @if(\Helper::checkRules('list-memberships,list-features'))
               <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/bill.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.memberships') }}</span><i class="angle fe fe-chevron-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}"></i>
                </a>
                <ul class="slide-menu">
                    @if(\Helper::checkRules('list-memberships'))
                    <li><a class="slide-item" href="{{ URL::to('/memberships') }}">{{ trans('main.packages') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-features'))
                    <li><a class="slide-item" href="{{ URL::to('/features') }}">{{ trans('main.features') }}</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(\Helper::checkRules('list-addons,list-extraQuotas'))
               <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/add.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.addons') }}</span><i class="angle fe fe-chevron-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}"></i>
                </a>
                <ul class="slide-menu">
                    @if(\Helper::checkRules('list-addons'))
                    <li><a class="slide-item" href="{{ URL::to('/addons') }}">{{ trans('main.addons') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-extraQuotas'))
                    <li><a class="slide-item" href="{{ URL::to('/extraQuotas') }}">{{ trans('main.extraQuotas') }}</a></li>
                    @endif
                </ul>
            </li>
            @endif



            
            @if(\Helper::checkRules('list-pages,list-sliders,list-contactUs,list-notifications,list-faq,list-sections'))
               <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/help.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.homePage') }}</span><i class="angle fe fe-chevron-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}"></i>
                </a>
                <ul class="slide-menu">
                    @if(\Helper::checkRules('list-pages'))
                    <li><a class="slide-item" href="{{ URL::to('/pages') }}">{{ trans('main.pages') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-sliders'))
                    <li><a class="slide-item" href="{{ URL::to('/sliders') }}">{{ trans('main.sliders') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-faqs'))
                    <li><a class="slide-item" href="{{ URL::to('/faqs') }}">{{ trans('main.faqs') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-sections'))
                    <li><a class="slide-item" href="{{ URL::to('/sections') }}">{{ trans('main.sections') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-contactUs'))
                    <li><a class="slide-item" href="{{ URL::to('/contactUs') }}">{{ trans('main.contactUs') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-notifications'))
                    <li><a class="slide-item" href="{{ URL::to('/pages/notifications') }}">{{ trans('main.notifications') }}</a></li>
                    @endif
                </ul>
            </li>
            @endif
    
           
            
            @if(\Helper::checkRules('list-notificationTemplates'))
            <li class="slide">
                <a class="side-menu__item {{ Active(URL::to('/notificationTemplates')) }}" href="{{ URL::to('/notificationTemplates') }}">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/administration.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.notificationTemplates') }}</span>
                </a>
            </li> 
            @endif
            
            @if(\Helper::checkRules('list-tickets,list-departments'))
               <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/tickets.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.tickets') }}</span><i class="angle fe fe-chevron-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}"></i>
                </a>
                <ul class="slide-menu">
                    @if(\Helper::checkRules('list-tickets'))
                    <li><a class="slide-item" href="{{ URL::to('/tickets') }}">{{ trans('main.tickets') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-departments'))
                    <li><a class="slide-item" href="{{ URL::to('/departments') }}">{{ trans('main.departments') }}</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(\Helper::checkRules('list-users,list-groups'))
               <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/users.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.users') }}</span><i class="angle fe fe-chevron-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}"></i>
                </a>
                <ul class="slide-menu">
                    @if(\Helper::checkRules('list-users'))
                    <li><a class="slide-item" href="{{ URL::to('/users') }}">{{ trans('main.users') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-groups'))
                    <li><a class="slide-item" href="{{ URL::to('/groups') }}">{{ trans('main.groups') }}</a></li>
                    @endif
                </ul>
            </li>
            @endif
            
            @if(\Helper::checkRules('list-coupons'))
            <li class="slide">
                <a class="side-menu__item {{ Active(URL::to('/coupons')) }}" href="{{ URL::to('/coupons') }}">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/coupon.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.coupons') }}</span>
                </a>
            </li> 
            @endif

            @if(\Helper::checkRules('list-bankAccounts'))
            <li class="slide">
                <a class="side-menu__item {{ Active(URL::to('/bankAccounts')) }}" href="{{ URL::to('/bankAccounts') }}">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/administration.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.bankAccounts') }}</span>
                </a>
            </li> 
            @endif
            
            @if(\Helper::checkRules('list-changeLogs,list-categories'))
               <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/logs.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.changeLogs') }}</span><i class="angle fe fe-chevron-{{ DIRECTION == 'ltr' ? 'right' : 'left' }}"></i>
                </a>
                <ul class="slide-menu">
                    @if(\Helper::checkRules('list-changeLogs'))
                    <li><a class="slide-item" href="{{ URL::to('/changeLogs') }}">{{ trans('main.changeLogs') }}</a></li>
                    @endif
                    @if(\Helper::checkRules('list-categories'))
                    <li><a class="slide-item" href="{{ URL::to('/categories') }}">{{ trans('main.categorys') }}</a></li>
                    @endif
                </ul>
            </li>
            @endif    

            <li class="slide">
                <a class="side-menu__item {{ Active(URL::to('/users/edit/'.USER_ID)) }}" href="{{ URL::to('/users/edit/'.USER_ID) }}">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/setting.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.account_setting') }}</span>
                </a>
            </li> 

            <li class="slide">
                <a class="side-menu__item {{ Active(URL::to('/logout')) }}" href="{{ URL::to('/logout') }}">
                    <div class="side-angle1"></div>
                    <div class="side-angle2"></div>
                    <div class="side-arrow"></div>
                    <img src="{{ asset('assets/dashboard/assets/images/logout.svg') }}" alt="">
                    <span class="side-menu__label">{{ trans('main.logout') }}</span>
                </a>
            </li> 
              
        </ul>
    </div>
</aside>
<!-- main-sidebar -->