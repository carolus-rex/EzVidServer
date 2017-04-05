<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
			View::share('view_name', $view->getName());
		});
		
		View::composer('vids.filters_form', function ($view){
			$view->with("show_all", Request::cookie("show_all", "true"));
			$view->with("show_unchecked", Request::cookie('show_unchecked', "false"));
			$view->with("show_checked", Request::cookie("show_checked", "false"));
			$view->with("show_aproved", Request::cookie("show_aproved", "false"));
		});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
