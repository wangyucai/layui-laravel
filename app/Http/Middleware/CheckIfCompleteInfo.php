<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Common\Enum\HttpCode;
use App\Exceptions\IfCompleteException;
use App\Exceptions\IfCheckUserInfoException;


class CheckIfCompleteInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 获取登录用户的真实姓名以及是否审核通过
        $my_real_name = Auth::guard('admin')->user()->real_name;
        $my_tel_hm = Auth::guard('admin')->user()->tel_hm;
        $perinfor_if_check = Auth::guard('admin')->user()->perinfor_if_check;
        // 未完善人事信息
        if (empty($my_real_name) && $my_tel_hm) throw new IfCompleteException(IfCompleteException::UNAUTHORIZED);
        // 提交人事信息--未审核
        if ($my_real_name && $perinfor_if_check==0) throw new IfCheckUserInfoException(IfCheckUserInfoException::UNAUTHORIZED);
        return $next($request);
    }
}
