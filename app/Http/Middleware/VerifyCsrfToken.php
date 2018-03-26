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
        ,'/admin/trainmodule/bmusers'
        ,'/admin/trainmodule/feedback'
        ,'/admin/infortech'
        ,'/admin/traindirection'
        ,'/admin/noticetype'
        ,'/admin/notice'
        ,'/admin/notice/upload'
        ,'/admin/readmynotice'
        ,'/admin/downattachment'
        ,'/admin/readmymessage'
        ,'/admin/trainnotice/enter'
        ,'/admin/email'
        ,'/admin/email/upload'
        ,'/admin/readmyemail'
        ,'/admin/professioncarcode'
        ,'/admin/informatization'
        ,'/admin/myinforcarnum'
        ,'/admin/informatization/upload'
        ,'/admin/professioncarmodule'
        ,'/admin/certificatebid'
        ,'/admin/managecertificate'
        ,'/admin/managecertificate/reporting'
        ,'/admin/managecertificate/check'
        ,'/admin/completeinfouser/edit'
        ,'/admin/exportuser'
        ,'/admin/assetunit'
        ,'/admin/equipmentasset'
        ,'/admin/warehouse'
        ,'/admin/equipmentasset/inbound'
        ,'/admin/equipmentasset/download'
        ,'/admin/assetclaim'
        ,'/admin/allassetclaim/check'
        ,'/admin/myassetclaims/download'
        ,'/admin/deviceidentity'
        ,'/admin/deviceidentity/bf'
        ,'/admin/inboundasset'
        ,'/admin/inboundasset/check'
        ,'/admin/myfixedasset'
        ,'/admin/myfixedasset/upload'
        ,'/admin/myfixedasset/back'
        ,'/admin/myassetdevice/back'
        ,'/admin/myassetdevice/download'
        ,'/admin/assetdevices/inbound'
        ,'/admin/deviceidentity/down'
        ,'/admin/deviceidentity/upload'
    ];
}
