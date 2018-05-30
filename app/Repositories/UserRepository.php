<?php
/**
 * Created by PhpStorm.
 * User: Настя
 * Date: 17.04.2018
 * Time: 11:54
 */

namespace Corp\Repositories;


use Corp\User;

class UserRepository extends Repository
{

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function addUser($request){
        if(\Gate::denies('create',$this->model)){
            abort(403);
        }

        $data = $request->all();

        $user = $this->model->create([
           'name' =>$data['name'],
           'login' =>$data['login'],
           'email' =>$data['email'],
           'password' =>bcrypt($data['password']),
        ]);

        if($user){
            $user->roles()->attach($data['role_id']);
        }

        return ['status'=>'Пользователь добавлен'];
    }

    public function updateUser($request,$user){

        if(\Gate::denies('edit',$this->model)){
            abort(403);
        }
        $data = $request->all();

       // dd($data);

        if(isset($data['password'])){
            $data['password'] = bcrypt($data['password']);
        }

        $user->fill($data)->update();

       $user->roles()->sync([$data['role_id']]);

        return ['status'=>'Пользователь изменен'];

    }

    public function deleteUser($user){
        if(\Gate::denies('edit',$this->model)){
            abort(403);
        }

        $user->roles()->detach();

        if($user->delete()){
            return ['status'=>'Пользователь удален'];
        }
    }
}