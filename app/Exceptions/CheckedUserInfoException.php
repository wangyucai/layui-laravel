<?php

namespace App\Exceptions;


use Exception;

class CheckedUserInfoException extends Exception
{
    const UNAUTHORIZED = 403;
    public static $errMsg = [
        CheckedUserInfoException::UNAUTHORIZED => '您的人事信息审核已通过，无需重复申请！'
    ];

    public $errorMsg = "";
    public $errorNo = "";

    function __construct($errorNo, $msg="")
    {
        if($errorNo && $msg) {
            $this->errorNo   = $errorNo;
            $this->errorMsg    = $msg;

            parent::__construct($msg, $errorNo);
            return true;
        }
        $this->errorNo   = $errorNo;

        if(!empty($msg)) {
            $this->errorMsg  = $msg;
        } else {
            $this->errorMsg  = CheckedUserInfoException::$errMsg[$errorNo];
        }

        parent::__construct($msg);
    }


    public function getErrorMsg(){

        return $this->errorMsg;

    }


    public function getErrorNo() {
        return $this->errorNo;
    }



}
