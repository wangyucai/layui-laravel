<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * 注册用户列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function registers()
    {
        return view('admin.register.registers');
    }

}
    