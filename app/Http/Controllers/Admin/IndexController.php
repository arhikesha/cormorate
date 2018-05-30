<?php

namespace Corp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Corp\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IndexController extends AdminController
{
    //
    public function __construct()
    {

        parent::__construct();





        $this->template = env('THEME').'.admin.index';
    }
    
    public function index(){
        $this->title = 'Панель администратора';

        if(Gate::denies('VIEW_ADMIN')) {
            echo "no prva";
        }
        return $this->renderOutput();
    }
}