<?php

namespace App\Providers;
use App\Models\UserManagement;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;


class ComposerServiceProvider extends ServiceProvider
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
        //		
		
	 view()->composer(

         	'*',

           'App\Http\Composers\SampleComposer'

  );
		
		
		
		
    }
}
