<?php

namespace Corp\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Corp\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        ///регулярное выражение для всех параметров url запроса
        Route::pattern('alias','[\w-]+') ;

        parent::boot();
        ////параметр Для Articles в админке
        ///Обработка url,сразу получили бы модель Articles(id)в методе idit
        ////В контроллере не получается ввести зависимость
        Route::bind('article',function($value){
            return \Corp\Article::where('alias',$value)->first();
        });////1 аргумент article-это гет мараметр маршута в одиночном числе(urt Articles)
        ///Можно в route поменять параметр /*'parameters'=>[
       // 'articles'=>'alias'  ]*/

        Route::bind('menu',function ($value){
            return \Corp\Menu::where('id',$value)->first();
        });

        Route::bind('user',function ($value){
            return \Corp\User::find($value);
        });


    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace,
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }
}
