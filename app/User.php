<?php

namespace Corp;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','login',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function articles(){
        return $this->hasMany('Corp\Article');
    }

    public function roles(){
        return $this->belongsToMany('Corp\Role','user_role');
    }
    //string , array('VIEW_ADMIN,'ADD_ARTICLES')
    public function canDo($permission, $require = false){
            if(is_array($permission)){
               foreach ($permission as $perName){

                   $perName = $this->canDo($perName);////вернет true || false

                   if($perName && !$require){
                       return true;
                   }
                   else if(!$perName && $require){
                       return false;
                   }
                   return $require;
                   /////ПЕРЕСМОТРЕТ 84 урок
               }
            }else{
                foreach($this->roles as $role){

                    foreach ($role->perms as $perm){
                        ///str_is - сравниает строки
                        if( str_is($permission, $perm->name)){
                            return true;
                        }
                    }
                }
            }

    }
///string ['role1',role2..]
///проверяет если и у пользователя роль
    public function hasRole($name, $require = false){
        if(is_array($name)){
            foreach ($name as $roleName){

                $hasRole = $this->hasRole($roleName);////вернет true || false

                if($hasRole && !$require){
                    return true;
                }
                else if(!$hasRole && $require){
                    return false;
                }
                return $require;
            }
        }else{
            foreach($this->roles as $role){
                    ///str_is - сравниает строки
                    if( $role->name == $name){
                        return true;
                }
            }
        }
        return false;

    }

}
