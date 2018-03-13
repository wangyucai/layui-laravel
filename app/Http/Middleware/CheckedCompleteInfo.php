<?php

namespace App\Http\Middleware;

use Closure;
use App\Model\Admin;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\CheckedUserInfoException;

class CheckedCompleteInfo
{
    /**
     * Handle an incoming request.
     * 人员管理系统---完善人事信息已审核通过的话跳转到我的个人资料页面
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 提交人事信息--已审核
        $my_real_name = Auth::guard('admin')->user()->real_name;
        $perinfor_if_check = Auth::guard('admin')->user()->perinfor_if_check;
        if ($my_real_name && $perinfor_if_check==1) throw new CheckedUserInfoException(CheckedUserInfoException::UNAUTHORIZED);
        return $next($request);
    }
}
