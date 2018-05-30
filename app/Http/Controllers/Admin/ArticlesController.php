<?php

namespace Corp\Http\Controllers\Admin;

use Corp\Article;
use Corp\Category;
use Corp\Http\Requests\ArticalRequest;
use Corp\Repositories\ArticlesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
class ArticlesController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(ArticlesRepository $a_rep)
    {

        parent::__construct();



        $this->a_rep = $a_rep;


        $this->template = env('THEME').'.admin.articles';
    }


    public function index()
    {
        //
        if(Gate::denies('VIEW_ADMIN_ARTICLES')) {
            echo "no prva";
        }

        $this->title = 'Менеджер статей';

        $articles = $this->getArticles();

       /// dd($articles);
        $this->content = view(env('THEME').'.admin.articles_content')->with('articles',$articles)->render();

        return $this->renderOutput();
    }


    public function getArticles()
    {
        return $this->a_rep->get();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        if(Gate::denies('save', new \Corp\Article)){
            abort('403');
        }

        $this->title = "Добавить новый материал";

        $categories = Category::select(['title','alias','parent_id','id'])->get();

       // dd($categories);

        $list = array();
        ///формирования категорий
        foreach ($categories as $category){
            if($category->parent_id == 0){
                $list[$category->title] = array();
            }else{
                $list[$categories->where('id',$category->parent_id)->first()->title][$category->id] = $category->title;///////////Смотри
           ////////https://laravelcollective.com/docs/5.3/html#drop-down-lists
            }
        }
        //dd($list);
        $this->content = view(env('THEME').'.admin.articles_create_content')->with('categories',$list)->render();
        
        return $this->renderOutput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    ////в этот метод передается данные на сохранение с метода Create
    public function store(ArticalRequest $request)
    {
        //
       // dd($request);
        $result = $this->a_rep->addArticle($request);
        
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

    /*
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /// route article -> Article
    public function edit(Article $articles )
    {

        //$article = Article::where('alias',$alias);
        // $article->get();
        //   dd($article->get());

        // dd($articles);

        if(Gate::denies('edit',new Article())){
            abort(403);
        }

        $articles->img = json_decode($articles->img);

        $categories = Category::select(['title','alias','parent_id','id'])->get();

        $list = array();
        ///формирования категорий
        foreach ($categories as $category){
            if($category->parent_id == 0){
                $list[$category->title] = array();
            }else{
                $list[$categories->where('id',$category->parent_id)->first()->title][$category->id] = $category->title;///////////Смотри
                ////////https://laravelcollective.com/docs/5.3/html#drop-down-lists
            }
        }

        $this->title = "редактоирование матерала -".$articles->title ;

        $this->content = view(env('THEME').'.admin.articles_create_content')->with(['categories'=>$list,'article'=>$articles])->render();

        return $this->renderOutput();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticalRequest $request, Article $article)
    {
        //
        $result = $this->a_rep->updateArticle($request,$article);

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
    public function destroy(Article $article)
    {
        //
        $result = $this->a_rep->deleteArticle($article);

        if(is_array($result) && !empty($result['error'])){
            return back()->with($result);
        }

        return redirect('/admin')->with($result);
    }
}
