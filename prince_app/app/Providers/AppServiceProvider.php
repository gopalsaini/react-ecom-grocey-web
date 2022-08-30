<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){
		
		Schema::defaultStringLength(191);
	   
		View::composer('layouts.app', function($view){   
			
			$categoryResult=[];
		
			$category=\App\Helpers\commonHelper::callAPI('GET','/categorylist');

			if($category->status==200){
				
				$categoryResult=json_decode($category->content,true);

			}
  
      $currency=\DB::table('currency_values')->get();

			return $view->with(['category'=>$categoryResult,'currency'=>$currency]);
		});
    }
}
