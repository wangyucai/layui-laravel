<?php

namespace App\Http\Middleware;

use App\Model\Log;
use App\Model\Admin;
use Illuminate\Support\Facades\Session;  
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class LogsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
     public function handle($request, Closure $next) {
        $method = strtolower($request->method());
        $routes = $request->path();
        $ip = $request->ip();
        $user_id = Auth::guard('admin')->user()->id;
        $user_name = Auth::guard('admin')->user()->username;
        $operator = Auth::guard('admin')->user()->real_name;
        $record = $this->getOperateLog($routes, $method);
        if ($record) {
            $data = array(
                'user_id' => $user_id,
                'user_name' => $user_name,
                'operator' => $operator,
                'method' => $method,
                'routes' => $routes,
                'record' => $record,
                'ip' => $ip,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            );
            Log::create($data);
        }
        return $next($request);
    }
    private function getOperateLog($routes, $method) {
        $operateLogConf = config('test');
        $key = $routes.'.'.$method;
        $record = isset($operateLogConf[$key]) && $operateLogConf[$key] ? $operateLogConf[$key] : '';
        if ($record) {
            return $record;
        } else {
            return false;
        }
    }
}
