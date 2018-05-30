<?php

namespace Corp\Providers;

use Corp\Menu;
use Corp\Permission;
use Corp\Policies\ArticlePolicy;
use Corp\Policies\MenusPolicy;
use Corp\Policies\PermissionPolicy;
use Corp\Policies\UserPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
//use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Corp\User;
use Corp\Article;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,////Регестрация политики
        Permission::class => PermissionPolicy::class,
        Menu::class => MenusPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


         Gate::define('VIEW_ADMIN',function(User $user){
           return $user->canDo(['VIEW_ADMIN','ADD_ARTICLES'],FALSE);
            
       });

        Gate::define('VIEW_ADMIN_ARTICLES',function(User $user){
        return $user->canDo(['VIEW_ADMIN_ARTICLES'],FALSE);

    });

        Gate::define('EDIT_USERS',function(User $user){
        return $user->canDo(['EDIT_USERS'],FALSE);

    });

        Gate::define('VIEW_ADMIN_MENU',function(User $user){
            return $user->canDo(['VIEW_ADMIN_MENU'],FALSE);

        });
       
    }
}
