<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Admin;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\RbacException;

class CheckIfRegister
{
    /**
     * Handle an incoming request.
     * 人员管理系统---完善人事信息只有注册用户能访问
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 根据tel_hm字段区分是否是系统管理员
        $tel_hm = Auth::guard('admin')->user()->tel_hm;
        if (!$tel_hm)
        throw new RbacException(RbacException::NOT_RULE);
        return $next($request);
    }
}
