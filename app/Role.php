<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //

    public function users(){
        return $this->belongsToMany('Corp\User','user_role');
    }

    public function perms(){
        return $this->belongsToMany('Corp\Permission','permission_role');
    }

    public function hasPermission($name, $require = false){
        if(is_array($name)){
            foreach ($name as $permissionName){

                $hasPermission = $this->hasPermission($permissionName);////вернет true || false

                if($hasPermission && !$require){
                    return true;
                }
                else if(!$hasPermission && $require){
                    return false;
                }
                return $require;
            }
        }else{
            foreach($this->perms()->get() as $permission){
                ///str_is - сравниает строки
                if( $permission->name == $name){
                    return true;
                }
            }
        }
        return false;

    }

    public function savePermissions($inputPermissions){

        if(!empty($inputPermissions)){
            $this->perms()->sync($inputPermissions);//sync -синхронизация связаных модель через связаную моделть
        }else{
            $this->perms()->detach();///detach -отвязка свззаные заиси
        }
        return true;
    }

}

