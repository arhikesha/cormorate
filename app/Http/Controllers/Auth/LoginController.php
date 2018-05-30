<?php

namespace Corp\Http\Controllers\Auth;

use Corp\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);

    }


    ///Переопределяет вид шаблона логина
    public function showLoginForm(){

        //$decode = ('$2y$10$UhypXhMWzV24GTWvv5orteRSHVm80Qbzr/LBW.0xFrVbiX.4cire6');


        $view = view(env("THEME").'.login');
        if(!$view){
            abort(404);
        }
        return $view->with('title','Вход на сайт');

    }

    public function username()
    {
        return 'login';
    }

}
