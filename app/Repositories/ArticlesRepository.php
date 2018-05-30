<?php
namespace Corp\Repositories;

use Corp\Article;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;
class ArticlesRepository extends Repository{

    public function __construct(Article $articles)
    {
        $this->model = $articles;
    }
    ////$alias - конкретная Article,$attr - есть ли коментарии
    public function one($alias,$attr=array()){
        $article = parent::one($alias,$attr);


        //ПРоверяем если есть коментарии в конктретном Article
        if($article && !empty($attr)) {
            $article->load('comments');
            $article->comments->load('user');
        }
        return $article;
    }

    public function addArticle($request)
    {
        if(Gate::denies('save', $this->model)){
            abort(403);
        }

        $data = $request->except('_token','image');///взять все поле кроме 

        if(empty($data)){
            return  array('error'=>'Нет данных');
        }

        if(empty($data['alias'])){
            $data['alias'] = $this->transliterate($data['title']);

        }
       //dd($data);
        if($this->one($data['alias'],false)){
            $request->merge(array('alias'=>$data['alias']));//marge-обьеденение массива
            $request->flash();///сохраняет все значения в импутах
           // dd($request);
            return ['error'=>"данный псевдоним уже используется"];

        }

            if($request->hasFile('image')){
                //$request->hasFile()-проверяет был лии загружен файл на сервер,1 агрумент поле откуда загружают(input)
                $image = $request->file('image');

                if($image->isValid()){

                    $str = str_random(8);///случайна строка

                    $obj = new \stdClass();///стандарный класс php
                    $obj->mini = $str.'_mini.jpg';
                    $obj->max = $str.'_max.jpg';
                    $obj->path = $str.'_path.jpg';

                    $img = Image::make($image);

                  //  dd($img);

                    /////////http://image.intervention.io/api/fit
                    $img->fit(Config::get('setting.image')['width'],
                        Config::get('setting.image')['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->path);////обрезает изображение

                    $img->fit(Config::get('setting.articles_img')['max']['width'],
                        Config::get('setting.articles_img')['max']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->max);

                    $img->fit(Config::get('setting.articles_img')['mini']['width'],
                        Config::get('setting.articles_img')['mini']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->mini);

                        $data['img'] = json_encode($obj);

                    $this->model->fill($data);///заполняем модель артикел данными
                   // dd($data);

                   if($request->user()->articles()->save($this->model)){
                        return ['status'=>'Материал добавлен'];
                    }
                }
            }else{
                $request->flash();///сохраняет все значения в импутах
                return ['error'=>'изображение не добавлено'];
            }
    }
    public function updateArticle($request,$article)
    {
        if(Gate::denies('edit', $this->model)){
            abort(403);
        }

        $data = $request->except('_token','image','_method');///взять все поле кроме

        if(empty($data)){
            return  array('error'=>'Нет данных');
        }
    ////провера на пустое значение импута алиас
        if(empty($data['alias'])){
            $data['alias'] = $this->transliterate($data['title']);

        }
        //dd($data);

        $result = $this->one($data['alias'],false);
        ////модель которую мы берем $result для редактирования и та которую получаем
        //через Url , и проверяем если id не совпадают
        if(isset($result->id) && $result->id != $article->id){
            $request->merge(array('alias'=>$data['alias']));//marge-обьеденение массива
            $request->flash();
            // dd($request);
            return ['error'=>"данный псевдоним уже используется"];

        }

        if($request->hasFile('image')){
            //$request->hasFile()-проверяет был лии загружен файл на сервер,1 агрумент поле откуда загружают(input)
            $image = $request->file('image');

            if($image->isValid()){

                $str = str_random(8);///случайна строка

                $obj = new \stdClass();///стандарный класс php
                $obj->mini = $str.'_mini.jpg';
                $obj->max = $str.'_max.jpg';
                $obj->path = $str.'_path.jpg';

                $img = Image::make($image);

                //  dd($img);

                /////////http://image.intervention.io/api/fit
                $img->fit(Config::get('setting.image')['width'],
                    Config::get('setting.image')['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->path);////обрезает изображение

                $img->fit(Config::get('setting.articles_img')['max']['width'],
                    Config::get('setting.articles_img')['max']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->max);

                $img->fit(Config::get('setting.articles_img')['mini']['width'],
                    Config::get('setting.articles_img')['mini']['height'])->save(public_path().'/'.env('THEME').'/images/articles/'.$obj->mini);

                $data['img'] = json_encode($obj);


            }
        }

        $article->fill($data);///заполняем модель артикел данными
        // dd($data);

        if($article->update()){
            return ['status'=>'Материал обновлен'];
        }

    }

    public function deleteArticle($article){

        if(Gate::denies('destroy', $article)){
            abort(403);
        }
        ////если к обращаемя к метод то обращаемся к конструктору запроса, если к свойству то вернет коллекцию
        $article->comments()->delete();

        if($article->delete()){
            return ['status'=>'Материал удален'];
        }
    }
}