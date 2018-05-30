<?php

namespace Corp\Http\Controllers;

use Corp\Repositories\ArticlesRepository;
use Corp\Repositories\MenusRepository;
use Corp\Repositories\PortfolioRepository;
use Corp\Repositories\SlidersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
class IndexController extends SiteController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct(SlidersRepository $s_rep, PortfolioRepository $p_rep,ArticlesRepository $a_rep)
    {
        parent::__construct(new MenusRepository(new \Corp\Menu() ) );

        $this->s_rep = $s_rep;
        $this->p_rep = $p_rep;
        $this->a_rep = $a_rep;
        $this->bar = 'right';//сайтбар с права
        $this->template = env('THEME').'.index';///использует настройки env
        ///данный настройка использует шаблон resuorces/view/pink
    }

    public function index()
    {

        $portfolios = $this->getPortfolio();
       // dd($portfolios);
        $content = view(env('THEME').'.content')->with('portfolios',$portfolios)->render();
        $this->vars = array_add($this->vars,'content',$content);

        $slideritem = $this->getSliders();
       // dd($slideritem);

        $sliders = view(env('THEME').'.slider')->with('sliders',$slideritem)->render();
        $this->vars = array_add($this->vars,'sliders',$sliders);
        

        $this->keywords = "Home Page";
        $this->meta_desc = "Home Page";
        $this->title = "Home Page";


        $articles = $this->getArticles();

       // dd($articles);
        $this->contentRightBar = view(env("THEME").'.indexBar')->with('articles',$articles)->render();
        
      return $this->renderOutput();
    }

    public  function getSliders(){
        $sliders = $this->s_rep->get();
        //isEmpty - если пустй вернет true
        if($sliders->isEmpty()){
            return false;
        }
        //$sliders->transform  - transform -это интерация(с callback function) типа foreach 
        $sliders->transform(function($item,$key){
            //После каждой итереции прибавляем путь к файлу

            $item->img = Config::get('setting.slider_path').'/'.$item->img;///прибаляем к картинке родительскую директорию
           // Config::get('setting.slider_path'- Доступ к config/setting slider_path
            return $item;
        });
           // dd($sliders);
            return $sliders;
    }

    protected function getPortfolio(){

        $portfolio = $this->p_rep->get('*',Config::get('setting.home_port_count'));

       // dd($portfolio);

        return $portfolio;
    }
    
    protected function getArticles(){
        $articles = $this->a_rep->get(['title','created_at','img','alias'],Config::get('setting.home_articles_count'));
        return $articles;
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
