<?php

namespace App\Exceptions;


use Exception;

class IfCompleteException extends Exception
{
    const UNAUTHORIZED = 403;
    public static $errMsg = [
        IfCompleteException::UNAUTHORIZED => '您没有完善人事信息'
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
            $this->errorMsg  = IfCompleteException::$errMsg[$errorNo];
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
