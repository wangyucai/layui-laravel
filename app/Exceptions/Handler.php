<?php

namespace App\Exceptions;

use App\Common\Enum\HttpCode;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof AuthenticationException) {
            if ($request->expectsJson()) return ajaxError('您未登录', HttpCode::UNAUTHORIZED);
            if (in_array('admin', $exception->guards())) return redirect('/admin/login');
        }

        if ($exception instanceof RbacException) {
            if ($request->expectsJson()) return ajaxError('您没有权限', HttpCode::FORBIDDEN);
            return redirect('/admin/forbidden');
        }
        if ($exception instanceof IfCompleteException) {
            if ($request->expectsJson()) return ajaxError('您没有完善人事信息', HttpCode::UNAUTHORIZED);
            return redirect('/admin/nocomplete');
        }
        if ($exception instanceof IfCheckUserInfoException) {
            if ($request->expectsJson()) return ajaxError('您的人事信息还未审核', HttpCode::UNAUTHORIZED);
            return redirect('/admin/nocheck');
        }
        if ($exception instanceof CheckedUserInfoException) {
            if ($request->expectsJson()) return ajaxError('您的人事信息审核已通过', HttpCode::UNAUTHORIZED);
            return redirect('/admin/checked');
        }
        return parent::render($request, $exception);
    }
}
