<?php

namespace Corp\Http\Controllers\Admin;

use Corp\Http\Requests\MenusRequest;
use Corp\Repositories\ArticlesRepository;
use Corp\Repositories\MenusRepository;
use Corp\Repositories\PortfolioRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Lavary\Menu\Menu;


class MenusController extends AdminController
{

    protected $m_rep;

    public function __construct(MenusRepository $m_rep, ArticlesRepository $a_rep, PortfolioRepository $p_rep)
    {
        parent::__construct();

        $this->m_rep = $m_rep;
        $this->a_rep = $a_rep;
        $this->p_rep = $p_rep;

        $this->template = env('THEME').'.admin.menus';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        if(Gate::denies('VIEW_ADMIN_MENU')){
            abort(403);
        }

        $menu = $this->getMenus();
       // dd($menu);

        $this->content = view(env('THEME').'.admin.menus_content')->with('menu',$menu)->render();

        return $this->renderOutput();

    }

    public function getMenus()
    {
        $menu = $this->m_rep->get();


        if ($menu->isEmpty()) {
            return false;
        }

       return \Menu::make('forMenuPart', function ($m) use ($menu) {

            foreach ($menu as $item) {
                if ($item->parent == 0) {
                    $m->add($item->title, $item->path)->id($item->id);
                } else {
                    if ($m->find($item->parent)) {
                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);
                    }
                }
            }
        });
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $this->title ="Новый пунк меню";

        $tmp = $this->getMenus()->roots();///roots()-вернет родетельские категории

        //при первом вызове $returnMenus- будет равен Null,
        ////Смотри Api Laravel reduce
        $menus = $tmp->reduce(function($returnMenus, $menu){

            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;

        },['0'=>'Родительский пунк меню']);///reduce()-будет выполнина для каждого елемента коллекции и
        ///возвращает результат преведущей инетерации

       // dd($menus);

        $categories = \Corp\Category::select(['title','alias','parent_id','id'])->get();

        $list = array();
        $list = array_add($list,'0','Не используется');
        $list = array_add($list,'parent','Раздел блог');

        foreach ($categories as $category){
            if($category->parent_id == 0){
                $list[$category->title] = array();
            }else{
                $list[$categories->where('id',$category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
       // dd($list);

        $articles = $this->a_rep->get(['id','title','alias']);

        $articles = $articles->reduce(function($returnArticles, $article){

            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;

        },[0=>'Не используется']);

       // dd($articles);

        $filters = \Corp\Filter::select('id','title','alias')->get()->reduce(function($returnFilrers,$filter){
            $returnFilrers[$filter->alias] = $filter->title;
            return $returnFilrers;
        },['parent'=>'Раздел портфолио']);

      //  dd($filters);

        $portfolios = $this->p_rep->get(['id','alias','title'])->reduce(function($returnPortfolios,$portfolio){
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        },[0=>'Не используется']);

        //dd($portfolios);

        $this->content = view(env('THEME').'.admin.menus_create_content')
            ->with(['menus'=>$menus,'categories'=>$list,'article'=>$articles,'portfolio'=>$portfolios,'filters'=>$filters])->render();

        return $this->renderOutput();



    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenusRequest $request)
    {
        //
        $result = $this->m_rep->addMenu($request);

        if(is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
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
    public function edit(\Corp\Menu $menu)
    {
        //
        //  dd($menu);
        $this->title ="Редактирование ссылки -". $menu->title;

        $type = false;
        $option = false;

        /////формирование редактированой ссылки!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
       // dd(app('router')->getRoutes()->match(app('request')->create($menu->path)));
        $route = (app('router')->getRoutes()->match(app('request')->create($menu->path)));

        $aliasRoute = $route->getName();
      //  dd($aliasRoute);
        $parameters = $route->parameters();
       // dd($parameters);

        if($aliasRoute == 'articles.index' || $aliasRoute == 'articlesCat'){
            $type = 'blogLink';
            $option = isset($parameters['cat_alias']) ? $parameters['cat_alias']:'parent';
        }else if($aliasRoute == 'articles.show'){
            $type = 'blogLink';
            $option = isset($parameters['alias']) ? $parameters['alias']:'';
        }else if($aliasRoute == 'portfolios.index') {
            $type = 'portfolioLink';
            $option = 'parent';
        }else if($aliasRoute == 'portfolios.show'){
                $type = 'portfolioLink';
                $option = isset($parameters['alias']) ? $parameters['alias']:'';
            }else{
            $type = 'customLink';
        }



      //  dump($type);
       // dump($option);


        $tmp = $this->getMenus()->roots();///roots()-вернет родетельские категории

        //при первом вызове $returnMenus- будет равен Null,
        ////Смотри Api Laravel reduce
        $menus = $tmp->reduce(function($returnMenus, $menu){

            $returnMenus[$menu->id] = $menu->title;
            return $returnMenus;

        },['0'=>'Родительский пунк меню']);///reduce()-будет выполнина для каждого елемента коллекции и
        ///возвращает результат преведущей инетерации

        // dd($menus);

        $categories = \Corp\Category::select(['title','alias','parent_id','id'])->get();

        $list = array();
        $list = array_add($list,'0','Не используется');
        $list = array_add($list,'parent','Раздел блог');

        foreach ($categories as $category){
            if($category->parent_id == 0){
                $list[$category->title] = array();
            }else{
                $list[$categories->where('id',$category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }
        // dd($list);

        $articles = $this->a_rep->get(['id','title','alias']);

        $articles = $articles->reduce(function($returnArticles, $article){

            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;

        },[0=>'Не используется']);

        // dd($articles);

        $filters = \Corp\Filter::select('id','title','alias')->get()->reduce(function($returnFilrers,$filter){
            $returnFilrers[$filter->alias] = $filter->title;
            return $returnFilrers;
        },['parent'=>'Раздел портфолио']);


        //  dd($filters);
        $portfolios = $this->p_rep->get(['id','alias','title'])->reduce(function($returnPortfolios,$portfolio){
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        },[0=>'Не используется']);

        //dd($portfolios);

        $this->content = view(env('THEME').'.admin.menus_create_content')
            ->with(['menus'=>$menus,'categories'=>$list,'article'=>$articles,'portfolio'=>$portfolios,
                'filters'=>$filters,'type'=>$type,'option'=>$option,'menu'=>$menu])->render();

        return $this->renderOutput();



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, \Corp\Menu $menu)
    {
        //
        $result = $this->m_rep->updateMenu($request, $menu);

        if(is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( \Corp\Menu $menu)
    {
        $result = $this->m_rep->deleteMenu( $menu);

        if(is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }
}
