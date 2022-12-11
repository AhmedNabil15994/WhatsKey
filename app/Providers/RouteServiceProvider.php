<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->mapGuestRoutes();
        $this->mapModuleRoutes();
        // $this->mapApiGuestRoutes();
        // $this->mapApiModuleRoutes();

        // $this->routes(function () {
        //     Route::prefix('api')
        //         ->middleware('api')
        //         ->namespace($this->namespace)
        //         ->group(base_path('routes/api.php'));

        //     Route::middleware('web')
        //         ->namespace($this->namespace)
        //         ->group(base_path('routes/web.php'));
        // });
    }

     protected function mapGuestRoutes()
    {
        @define('DATE_TIME', date("Y-m-d H:i:s"));
        foreach ($this->centralDomains() as $domain) {
            Route::middleware('general')
                ->domain($domain)
                ->namespace($this->namespace)
                ->group(function () {
                    require app_path('Modules/Central/Auth/routes.php');
                    require app_path('Modules/Central/Home/routes.php');
            });
        }
    }

    protected function mapModuleRoutes()
    {
        foreach ($this->centralDomains() as $domain) {        
            Route::middleware('centralAuth')
                ->domain($domain)
                ->namespace($this->namespace)
                ->group(function (){
                    require app_path('Modules/Central/Dashboard/routes.php');
                    require app_path('Modules/Central/User/routes.php');
                    require app_path('Modules/Central/Group/routes.php');
                    require app_path('Modules/Central/Membership/routes.php');
                    require app_path('Modules/Central/Feature/routes.php');
                    require app_path('Modules/Central/Addons/routes.php');
                    require app_path('Modules/Central/ExtraQuota/routes.php');
                    require app_path('Modules/Central/FAQ/routes.php');
                    require app_path('Modules/Central/Changelog/routes.php');
                    require app_path('Modules/Central/Category/routes.php');
                    require app_path('Modules/Central/Coupon/routes.php');
                    require app_path('Modules/Central/BankAccount/routes.php');
                    require app_path('Modules/Central/NotificationTemplate/routes.php');
                    require app_path('Modules/Central/Page/routes.php');
                    require app_path('Modules/Central/Slider/routes.php');
                    require app_path('Modules/Central/DesignSection/routes.php');
                    require app_path('Modules/Central/ContactUs/routes.php');
                    require app_path('Modules/Central/Ticket/routes.php');
                    require app_path('Modules/Central/Department/routes.php');
                    require app_path('Modules/Central/Client/routes.php');
                    require app_path('Modules/Central/TransferRequest/routes.php');
                    require app_path('Modules/Central/Invoice/routes.php');
            });
        }
    }

    protected function mapApiGuestRoutes()
    {
        foreach ($this->centralDomains() as $domain) {
            Route::middleware('apiGeneral')
                ->domain($domain)
                ->prefix('api')
                ->namespace($this->namespace)
                ->group(function () {
                    require app_path('Modules/Mobile/Auth/routes.php');
            });
        }
    }

    protected function mapApiModuleRoutes()
    {
        foreach ($this->centralDomains() as $domain) {        
            Route::middleware('apiWithAuth')
                ->domain($domain)
                ->prefix('api')
                ->namespace($this->namespace)
                ->group(function (){
                    require app_path('Modules/Mobile/LiveChat/routes.php');
                    require app_path('Modules/Mobile/Profile/routes.php');
            });
        }
    }

    protected function centralDomains(): array
    {
        return config('tenancy.central_domains');
    }
    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
