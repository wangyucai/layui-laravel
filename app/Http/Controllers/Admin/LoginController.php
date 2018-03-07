<?php

namespace App\Http\Controllers\Admin;

use App\Common\Enum\HttpCode;
use App\Http\Controllers\Controller;
use App\Service\AdminService;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\LoginEvent; 
use App\Events\AnnualEvent; 
use Jenssegers\Agent\Agent; 
use Carbon\Carbon;
use App\Model\Identifyinfo;  
use App\Model\CertificateBid;

class LoginController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();
        if ($user) return redirect('/admin/index');
        return view('admin.login.login');
    }

    public function signIn(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'code' => 'required'
        ]);
        if (strtolower($request->code) != session('loginCaptcha')) return ajaxError('验证码错误', HttpCode::BAD_REQUEST);
        $service = new AdminService();
        $re = $service->login($request->username, $request->password, (bool)$request->remember);
        if ($re) {
            //登录成功，触发事件
            event(new LoginEvent(Auth::guard('admin')->user(), new Agent(), \Request::getClientIp(), Carbon::now()));
            event(new AnnualEvent(Auth::guard('admin')->user(), Carbon::now()));
            return ajaxSuccess();
        } else {
            return ajaxError($service->getError(), $service->getHttpCode());
        }
    }

    public function logOut()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }

    public function captcha() {
        $phraseBuilder = new PhraseBuilder(4);
        $builder = new CaptchaBuilder(null, $phraseBuilder);
        $builder->build();
        $phrase = strtolower($builder->getPhrase());
        session(['loginCaptcha' => $phrase]);
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
}