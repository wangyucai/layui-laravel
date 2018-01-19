<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/admin/user'
        ,'/admin/rule'
        ,'/admin/role'
        ,'/admin/register'
        ,'/admin/registeruser'
        ,'/admin/completeuserinfo'
        ,'/admin/completeinfouser'
        ,'/admin/institutioncode'
        ,'/admin/business'
        ,'/admin/inscertificate'
        ,'/admin/completeidentifyinfo'
        ,'/admin/completeidentifyinfo/upload'
        ,'/admin/myidentifynum'
        ,'/admin/company'
        ,'/admin/mechanismcode'
        ,'/admin/mymechanismcode'
        ,'/admin/trainmodule'
        ,'/admin/infortech'
        ,'/admin/traindirection'
        ,'/admin/noticetype'
        ,'/admin/notice'
    ];
}
