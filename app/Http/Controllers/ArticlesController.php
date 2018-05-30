<?php

namespace Corp\Http\Controllers;

use Corp\Category;
use Corp\Repositories\CommentsRepository;
use Illuminate\Http\Request;
use Corp\Repositories\MenusRepository;
use Corp\Repositories\ArticlesRepository;
use Corp\Repositories\PortfolioRepository;



class ArticlesController extends SiteController
{

    public function __construct( PortfolioRepository $p_rep,ArticlesRepository $a_rep, CommentsRepository $c_rep)
    {
        parent::__construct(new MenusRepository(new \Corp\Menu() ) );
        
        $this->p_rep = $p_rep;
        $this->a_rep = $a_rep;
        $this->c_rep = $c_rep;
        $this->bar = 'right';//сайтбар с права
        $this->template = env('THEME').'.articles';///использует настройки env
        ///данный настройка использует шаблон resuorces/view/pink
    }

    public function index(Request $request, $cat_alias = false)
    {
       /* $i = $request->cat_alias;
        dd($i);*/
        /*
        $art = Article::find(1);
        dd($art->category->title);*/
       /* $comm = Comment::find(1);
        dd($comm->user);*/
        $this->title = "string";
        $this->keywords = "string";
        $this->meta_desc = "string";

        $articles = $this->getArticles($cat_alias);


        $content = view(env("THEME").'.articles_content')->with('articles',$articles)->render();
        $this->vars = array_add($this->vars,'content',$content);

        $comments = $this->getComments(config('setting.recent_comments'));
        $portfolios = $this->getPortfolios(config('setting.recent_portfolios'));

        $this->contentRightBar = view(env("THEME").'.articlesBar')->with(['comments'=>$comments,'portfolios'=>$portfolios]);

        // dd($articles);
        //   dd($content);

        return $this->renderOutput();
    }

    protected function getArticles($alias = false){

        $where = false;

        if($alias){
            //WHERE alias = $alias
            $id  = Category::select('id')->where('alias',$alias)->first()->id;
           // dd($id);
            ///Where category_id = $id
            $where = ['category_id',$id];

        }


        $articles = $this->a_rep->get(['id','title','alias','created_at','img','desc','user_id','category_id','keywords','meta_desc'],false,true,$where);
        //dd($articles);
        if($articles){
            $articles->load('user','category','comments');/// load()  - Подгружает из связаной модели(автоматизация запроса)
            ////Подкгружаем связаные модели - ЖАДНАЯ ЗАГРУЗКА
            ///АРГУМЕНТЫ Load() -это методы  модели Article
        }


        return $articles;
    }

    public function getComments($take){

        $comments= $this->c_rep->get(['id','text','name','email','site','article_id','user_id'],$take);

        if($comments){
            $comments->load('article','user');/// load()  - Подгружает из связаной модели(автоматизация запроса)
            ////Подкгружаем связаные модели - ЖАДНАЯ ЗАГРУЗКА
            ///АРГУМЕНТЫ Load() -это методы  модели Comment
        }

        //dd($comments);
        return $comments;
    }

    public function getPortfolios($take){

        $portfolios= $this->p_rep->get(['title','text','alias','customer','img','filter_alias'],$take);

        //dd($portfolios);

        return $portfolios;

    }

    public function show($alias=false) {

        $article = $this->a_rep->one($alias,['comments'=>true]);
        

        if($article) {
            $article->img = json_decode($article->img);
        }
            ////groupBy() - Группировка в древовидном виде
        //dd($article->comments->groupBy('parent_id'));
    if($article) {
        $this->title = $article->title;
        $this->keywords = $article->keywords;
        $this->meta_desc = $article->meta_desc;

    }
        $content = view(env("THEME").'.article_content')->with('article',$article)->render();
        $this->vars = array_add($this->vars,'content',$content);



        $comments = $this->getComments(config('setting.recent_comments'));
        $portfolios = $this->getPortfolios(config('setting.recent_portfolios'));

        $this->contentRightBar = view(env("THEME").'.articlesBar')->with(['comments'=>$comments,'portfolios'=>$portfolios]);

        return $this->renderOutput();
    }

}
