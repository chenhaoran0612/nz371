<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use App\UserGroup;

class Permission
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if (!$user) {
            return redirect('/login');
        }
        $parent = $user->getParent();
        $email =  $parent ? $parent->email : 'admin@lanxion.com';
        $user = $this->auth->user();
        $permission = $user->getPermissionArr();
        
        $role = $user->role;
        if ($request->ajax()) {
            //超级管理员加入只有查看权限没有操作权限
            // if($role == 'admin'){
            //     return abort(403, $email);
            // }
            //目前只控制到页面，不对方法进行控制
            return $next($request);
        }
        $route = $request->route();

        $permisssionName = "";

        if(is_object($route)) {
            $controller = $route->getActionName();
            $permisssionName = str_replace('Controller@', '_', class_basename($controller));
            $permisssionName = strtolower($permisssionName);
        }
        if( $role != 'admin'){
            if ( in_array($permisssionName, $permission) ) {
                //有权限
                return $next($request);
            }
        } else {
            //超级管理员用户 
            return $next($request);
        }
        return abort(403);
    }
}
