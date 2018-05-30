<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::resource('/','IndexController',[
                                'only'=>['index'],///только метод index (route:list)
                                'names'=>[
                                   'index'=>'home'///Псевдоним
                                ]
                                ]);
Route::resource('portfolios','PortfolioController',[
                                    'parameters'=>[
                                        'portfolios'=>'alias'
                                    ]
                                                ]);
Route::resource('articles','ArticlesController',[
                                'parameters'=>[
                                    'articles'=>'alias'
                                ]
                                                ]);
Route::get('articles/cat/{cat_alias?}',['uses'=>'ArticlesController@index','as'=>'articlesCat'])
    ->where('cat_alias','[\w-]+');


Route::resource('comment','CommentController',['only'=>['store']]);

Route::match(['get','post'],'/contacts',['uses'=>'ContactController@index','as'=>'contacts']);

Route::get('login','Auth\LoginController@showLoginForm');
Route::post('login','Auth\LoginController@login');
Route::get('logout','Auth\LoginController@logout');

//admin/
Route::group(['prefix'=>'admin','middleware'=> 'auth'],function(){

    //admin
    Route::get('/',['uses'=> 'Admin\IndexController@index','as'=>'adminIndex']);
///articles
    Route::resource('/articles','Admin\ArticlesController',[
        'as'=>'admin',
        /*'parameters'=>[
            'articles'=>'alias'
        ]*/
    ]);

    Route::resource('/permissions','Admin\PermissionsController',[
        'as'=>'admin',
    ]);

    Route::resource('/menus','Admin\MenusController',[
        'as'=>'admin',
    ]);
    
    Route::resource('/users','Admin\UsersController',[
         'as'=>'admin',
    ]);

});