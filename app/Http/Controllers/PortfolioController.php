<?php

namespace Corp\Http\Controllers;

use Corp\Repositories\MenusRepository;
use Corp\Repositories\PortfolioRepository;
use Illuminate\Http\Request;

class PortfolioController extends SiteController
{
    public function __construct( PortfolioRepository $p_rep)
    {
        parent::__construct(new MenusRepository(new \Corp\Menu() ) );

        $this->p_rep = $p_rep;


        $this->template = env('THEME').'.portfolios';///использует настройки env
        ///данный настройка использует шаблон resuorces/view/pink
    }

    public function index()
    {
        /* $i = $request->cat_alias;
         dd($i);*/
        /*
        $art = Article::find(1);
        dd($art->category->title);*/

        /* $comm = Comment::find(1);
         dd($comm->user);*/
        $this->title = "Портфолио";
        $this->keywords = "Портфолио";
        $this->meta_desc = "Портфолио";

        $portfolios = $this->getPortfolios();

      //  dd($portfolios);


        $content = view(env("THEME").'.portfolios_content')->with('portfolios',$portfolios)->render();
        $this->vars = array_add($this->vars,'content',$content);



        return $this->renderOutput();
    }

    private function getPortfolios($take = false,$pagination=true){

        $portfolios = $this->p_rep->get('*',$take,$pagination);

      if($portfolios) {
          $portfolios->load('filter');
      }

        return $portfolios;
    }

    public function show($alias=false) {

        $portfolio = $this->p_rep->one($alias);//one - cмотри репозитории


       //dd($portfolio);

        $this->title = $portfolio->title;
        $this->keywords = $portfolio->keywords;
        $this->meta_desc = $portfolio->meta_desc;

        $portfolios = $this->getPortfolios(config('setting.other_portfolios'),false);


        $content = view(env("THEME").'.portfolio_content')->with(['portfolio'=>$portfolio,'portfolios'=>$portfolios])->render();
        $this->vars = array_add($this->vars,'content',$content);






        return $this->renderOutput();
    }
}
