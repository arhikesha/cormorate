<?php
namespace Corp\Repositories;

use Config;

abstract class Repository{

    protected $model = false;

    public function get($select = '*',$take = false,$pagination=false,$where=false)
    {
        $bilder = $this->model->select($select);
        if($take){
            ///если не false вызываем метод take -это количество строк которые надо выбрать с таблицы БД
            $bilder->take($take);
        }

        if($where) {
            $bilder->where($where[0],$where[1]);
        }

        if($pagination){
            return $this->check($bilder->paginate(Config::get('setting.paginate') ) );
        }


       // dd($bilder);

        return $this->check($bilder->get());
    }

    protected function check($result){
        if($result->isEmpty()){
            return false;
        }

        $result->transform(function($item,$key){
            ///Проверка если это строа И это обьект json И json_last_error - не вернула ошибку JSON_ERROR_NONE
            if(is_string($item->img) && is_object(json_decode($item->img)) && (json_last_error() == JSON_ERROR_NONE) ){
                ///Декодируем json форма и формируем обьект
                $item->img = json_decode($item->img);
            }


            return $item;
        });

        return $result;

    }
    
    public function one($alias,$attr=array()){
        
        $result = $this->model->where('alias',$alias)->first();
        
        return $result;
    }

    public function transliterate($string){
        $str = mb_strtolower($string,'UTF-8');

        $leter_array = array(
            'a'=>'а',
            'b'=>'б',
            'v'=>'в',
            'g'=>'г',
            'd'=>'д',
            'e'=>'е,э',
            'jo'=>'ё',
            'zh'=>'ж',
            'z'=>'з',
            'i'=>'и',
            'ji'=>'ё',
            'j'=>'й',
            'k'=>'к',
            'l'=>'л',
            'm'=>'м',
            'n'=>'н',
            'o'=>'о',
            'p'=>'п',
            'r'=>'р',
            's'=>'с',
            't'=>'т',
            'u'=>'у',
            'f'=>'ф',
            'kh'=>'х',
            'ts'=>'ц',
            'ch'=>'ч',
            'sh'=>'ш',
            'shch'=>'щ',
            ''=>'ъ',
            'y'=>'ы',
            ''=>'ь',
            'yu'=>'ю',
            'ya'=>'я',
        );

        foreach ($leter_array as $leter => $kyr) {
            $kyr = explode(',',$kyr);

            $str = str_replace($kyr,$leter,$str);

        }
        ///  A-Za-z0-9 ,\s-пробел,|-или,^-отрицание
        $str = preg_replace('/(\s|[^A-Za-z0-9\-])+/','-',$str);
       
        $str = trim($str,'-');

        return $str;
    }
}