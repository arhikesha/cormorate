<?php

namespace Corp\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //@set($i,10)
        ///Передаем содежимое в шаблонизатор blade
       Blade::directive('set',function($exp){
           list($name,$val) = explode(',',$exp);
           
           return "<?php $name = $val ?>";
       });

        DB::listen(function($query){
        ////Показывает все sql запросы
           // echo '<h1>'.$query->sql.'</h1>';

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
