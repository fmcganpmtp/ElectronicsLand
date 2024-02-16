<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
       // $view->with('logo', Generalsetting::where('item','company_logo')->first());
        //View::composer('*', 'App\Http\View\LogoComposer');
        View::composer('*', function($view)
    {
        $test="testing";
        $view->with('text',$test); 
    }
    );
    }
}
