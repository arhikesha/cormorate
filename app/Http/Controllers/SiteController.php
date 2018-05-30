<?php

namespace Corp\Http\Controllers;

use Corp\Repositories\MenusRepository;
use Illuminate\Http\Request;
use Lavary\Menu\Menu;
////РОДИтельский контроллер
class SiteController extends Controller
{
    //
    
    protected $p_rep;///портфолий репозиторй
    protected $s_rep;///слайдер репозиторй
    protected $a_rep;///articles репозиторй
    protected $m_rep;///menu репозиторй
    protected $c_rep;///comment репозиторй

    protected $keywords;
    protected $meta_desc;
    protected $title;

    protected $template;//тут будет хранится имя шаблона
    
    protected $vars = array();///массив передаваемых значений
    
    protected $contentRightBar = false;//еесли правый сайтбар
    protected $contentLeftBar = false;//еесли левый сайтбар
    
    protected $bar = 'no';//если есть сайтбар
    
    public function __construct(MenusRepository $m_rep)
    {
        $this->m_rep = $m_rep;//сохраняет менюрепозиторий
    }
    
    protected function renderOutput(){

        $menu = $this->getMenu();//вызов ниже метода
        //dd($menu);
        
        $navigation = view(env('THEME').'.navigation')->with('menu',$menu)->render();///render- преобразует в строку
        $this->vars = array_add($this->vars,'navigation',$navigation);///добавлеем в массив значения

        if($this->contentRightBar){
            $rightBar = view(env("THEME").'.rightBar')->with('content_rightBar',$this->contentRightBar)->render();
            $this->vars = array_add($this->vars,'rightBar',$rightBar);///добавлеем в массив значения
        }
        ///bar - Это с какой стороны будет сайт бар left,right или No
        if($this->contentLeftBar){
            $leftBar = view(env("THEME").'.leftBar')->with('content_leftBar',$this->contentLeftBar)->render();
            $this->vars = array_add($this->vars,'leftBar',$leftBar);///добавлеем в массив значения
        }

        $this->vars = array_add($this->vars,'bar',$this->bar);

        $this->vars = array_add($this->vars,'keywords',$this->keywords);
        $this->vars = array_add($this->vars,'meta_desc',$this->meta_desc);
        $this->vars = array_add($this->vars,'title',$this->title);


        $footer = view(env('THEME').'.footer')->render();
        $this->vars = array_add($this->vars,'footer',$footer);

        return view($this->template)->with($this->vars);
    }
    
    public function getMenu(){
        ////получние данных репозитоия меню
        $menu = $this->m_rep->get();
       // dd($menu);

        ////Скачаный репозиторий меню
        $mBulder = \Menu::make('MyNav',function($m) use ($menu){
          //use ($menu)--доступ к переменной меню
            //$m - это обьект $mBulder
            foreach($menu as $item){
                if($item->parent == 0){
                    $m->add($item->title,$item->path)->id($item->id);
                }else{
                    if($m->find($item->parent)){
                        $m->find($item->parent)->add($item->title,$item->path)->id($item->id);
                    }
                }
            }
        });

      //  dd($mBulder);

        return $mBulder;
    }
}
