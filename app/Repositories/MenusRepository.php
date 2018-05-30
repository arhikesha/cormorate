<?php
namespace Corp\Repositories;

use Corp\Menu;
use Illuminate\Support\Facades\Gate;
class MenusRepository extends Repository{
    
    public function __construct(Menu $menu)
    {
        $this->model = $menu;
    }

    public function addMenu($request)
    {
        if(Gate::denies('save', $this->model)){
            abort(403);
        }

        $data = $request->only('type','title','parent');
       // dd($data);
       // dd($request->all());

        if(empty($data)){
            return ['error'=>'нет данных'];
        }

          //  dd($data['type']);
        switch($data['type']){

            case'customLink':
                $data['path'] = $request->input('custom_link');
             break;

            case'blogLink':
               if($request->input('category_alias')){
                   if($request->input('category_alias') == 'parent'){
                       $data['path'] = route('articles.index');
                   }else{
                       $data['path'] = route('articlesCat',['cat_alias'=>$request->input('category_alias')]);
                   }
               }

               else if($request->input('articles_alias')){
                   $data['path'] = route('articles.show',['alias'=>$request->input('articles_alias')]);
                  // dd( route('articles.show',['alias'=>$request->input('articles_alias')]));
               }
                break;

            case 'portfolioLink':
                if($request->input('filter_alias')){
                    if($request->input('filter_alias') == 'parent'){
                        $data['path'] =route('portfolios.show',['alias'=>$request->input('portfolio_alias') ]);
                    }
                }

                else if($request->input('portfolio_alias')){
                    $data['path'] = route('portfolios.show',['alias'=>$request->input('portfolio_alias') ]);
                }

                break;

        }
      //dd($request->all());
        unset($data['type']);
        //(route('portfolios.show',['alias'=>$request->input('portfolio_alias')]));
       // dd($data);
        if($this->model->fill($data)->save())///filL--заполняем текушую модель данными
        {
            return ['status'=>'Ссылка добавлена'];
        }


    }

    public function updateMenu($request, $menu)
    {
        if(Gate::denies('save', $this->model)){
            abort(403);
        }

        $data = $request->only('type','title','parent');
        // dd($data);

        if(empty($data)){
            return ['error'>'нет данных'];
        }

        switch($data['type']){

            case'customLink':
                $data['path'] = $request->input('custom_link');
                break;

            case'blogLink':
                if($request->input('category_alias')){
                    if($request->input('category_alias') == 'parent'){
                        $data['path'] = route('articles.index');
                    }else{
                        $data['path'] = route('articlesCat',['cat_alias'=>$request->input('category_alias')]);
                    }
                }

                else if($request->input('articles_alias')){
                    $data['path'] = route('articles.show',['alias'=>$request->input('articles_alias')]);
                    // dd( route('articles.show',['alias'=>$request->input('articles_alias')]));
                }
                break;

            case 'portfolioLink':
                if($request->input('filter_alias')){
                    if($request->input('filter_alias') == 'parent'){
                        $data['path'] =route('portfolios.show',['alias'=>$request->input('portfolio_alias') ]);
                    }
                }

                else if($request->input('portfolio_alias')){
                    $data['path'] = route('portfolios.show',['alias'=>$request->input('portfolio_alias') ]);
                }

                break;

        }
        // dd($request->all());
        unset($data['type']);
        //(route('portfolios.show',['alias'=>$request->input('portfolio_alias')]));
        // dd($data);
        if($menu->fill($data)->update())///filL--заполняем текушую модель данными
        {
            return ['status'=>'Ссылка обновлена'];
        }


    }

    public function deleteMenu($menu)
    {
        if(Gate::denies('save', $this->model)){
            abort(403);
        }

        if($menu->delete()){
            return ['status'=>'Ссылка удалена'];
        }
    }

}