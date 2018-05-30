<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    ///для массового заполнения
    protected $fillable = ['title','img','alias','text','desc','keywords','meta_desc','category_id','user_id'];


    public  function user(){
        return $this->belongsTo('Corp\User');
    }

    public  function category(){
        return $this->belongsTo('Corp\Category');
    }
    
    public  function comments(){
        return $this->hasMany('Corp\Comment');
    }
    
}
