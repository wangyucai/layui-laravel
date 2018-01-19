<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Admin;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\RbacException;

class EditUserButton
{
    /**
     * Handle an incoming request.
     * 除了超级管理员外，只有本级管理员可以编辑本单位人员的信息
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 获取编辑用户的uid
        $uid = $request->id;
        // 获取编辑用户的单位代码
        $edit_company_dm = Admin::where('id',$uid)->value('company_dwdm');
        // 获取登录用户的单位代码
        $my_company_dm = Auth::guard('admin')->user()->company_dwdm;
        // 获取超级管理员的id
        $superadmin = Auth::guard('admin')->user()->id;
        if($edit_company_dm != $my_company_dm && $superadmin != 1)
        throw new RbacException(RbacException::NOT_RULE);
        return $next($request);
    }
}
