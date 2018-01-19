<?php

namespace App\Http\Middleware;

use App\Exceptions\RbacException;
use App\Model\Admin;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Rbac
{
    protected $prefix = 'App\\Http\\Controllers\\Admin\\';
    protected $rulesCacheKey = 'rules_cache_v1';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currentRule = $this->getCurrentRule();
        $rules = $this->getRules();
        if (!in_array($currentRule, $rules))
        throw new RbacException(RbacException::NOT_RULE);
        return $next($request);
    }
    /**
     *  获取当前的控制器或者方法名
     *  如：Registercontroller@registerList
    */
    public function getCurrentRule()
    {
        // "App\Http\Controllers\Admin\Registercontroller@registerList"
        $origRule = Route::current()->getActionName();
        $rule = substr($origRule, strlen($this->prefix));
        return strtolower($rule);
    }
    /**
     *  获取用户是否有当前路由的权限
    */
    public function getRules()
    {
        $id = Auth::guard('admin')->id();
        if (!$id) return [];
        $admin = new Admin();
        return $admin->getAdminRules($id);
    }
}
