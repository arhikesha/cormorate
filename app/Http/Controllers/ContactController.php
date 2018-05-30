<?php

namespace Corp\Http\Controllers;

use Corp\Repositories\MenusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends SiteController
{
    //

    public function __construct()
    {
        parent::__construct(new MenusRepository(new \Corp\Menu() ) );


        $this->bar = 'left';//сайтбар с права
        $this->template = env('THEME').'.contacts';

    }

    public function index(Request $request){

        if($request->isMethod('post')){

            $messages = [
                'required' =>'Поле :attribute Обьязательно к заполнению',
                'email' =>'Поле :attribute должно содержать правильный имейл',
            ];

            $this->validate($request,[
               'name'=>'required|max:255',
               'email'=>'required|email',
               'text'=>'required',
            ],$messages);

            $data = $request->all();


            $result = Mail::send(env('THEME').'.email',['data'=>$data],function($m) use ($data){
                
                $mail_admin = env('MAIL_ADMIN');


               $m->from($data['email'],$data['name']);///От кого пришли данные

                $m->to($mail_admin,'Mr.Admin')->subject('Question');///К кому пришли
               // subject('Question')- Тема письма
            });

            if($result){
                return redirect()->route('contacts')->with('status','Email is send');
            }
        }




        $this->title = "Контакты";

        $content = view(env('THEME').'.contact_content')->render();
        $this->vars = array_add($this->vars,'content',$content);

        $this->contentLeftBar = view(env("THEME").'.contact_bar')->render();

        return $this->renderOutput();

    }
}
