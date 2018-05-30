<?php

namespace Corp\Http\Controllers;

use Corp\Article;
use Corp\Comment;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class CommentController extends SiteController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //except - какие поля не надо сохранять

        $data = $request->except('_token','comment_post_ID','comment_parent');

        $data['article_id'] = $request->input('comment_post_ID');
        $data['parent_id'] = $request->input('comment_parent');
       // dd($data);

        $validator = Validator::make($data,[
                                'article_id'=>'integer|required',
                                'parent_id'=>'integer|required',
                                'text'=>'string|required',
        ]);
        //sometimes - дополнительный набор правил в зависимости от условий
        $validator->sometimes(['name','email'],'required:255',function($input){

            return !Auth::check();//вернет истину если не зарегестрирован
        });
        //fails - вернет истину если при валидации не прошла усешно
        if($validator->fails()){
            //Response::json() - вернет ответ что пользователь сделал не правильно
            return Response::json(['error'=>$validator->errors()->all()]);
        }

        $user = Auth::user();
       // dd($user);

        //$arra = array('name'=>'oleg','text'=>'zacha','site'=>'yeild');

        $comment = new Comment($data);///в контсруктор класса предается массив с данными,если поля массива соответствют колонкам в БД то они записываются в значение

        //dd($comment);
        
        if($user) {
            $comment->user_id  = $user->id;
            $comment->name = $user->name;
            $comment->email = $user->email;
        }
        //dd($comment);
        $post = Article::find($data['article_id']);
        //dd($post);
        //dd($post->comments()->where('user_id',$user->id)->get());
        //dd($user);
        $post->comments()->save($comment);
        
        ///формирования ответа пользователю
        $comment->load('user');
        $data['id'] = $comment->id;
        
        $data['email'] = (!empty($data['email'])) ? $data['email'] : $comment->user->email;
        $data['name'] = (!empty($data['name'])) ? $data['name'] : $comment->user->name;
        
        
        $data['hash'] = md5($data['email']);
        
        $view_comment = view(env('THEME').'.content_one_comment')->with('data',$data)->render();
        
        return Response::json(['success'=> true,'comment'=>$view_comment,'data'=>$data]);
        
        
       // echo json_encode(['hello'=>'world']);
       // exit();
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
