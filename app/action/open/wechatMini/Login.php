<?php

namespace action\open\wechatMini;

use core\Action;
use base\Input;

class Login extends Action
{
    public function index()
    {
        $code = Input::any(CODE, '');
        $iv = Input::any(IV, '');
        $encryptedData = Input::any(ENCRYPTEDDATA, '');
        $openAccount = Input::any(WECHATMINI, '');
        $userInfo = $iv && $encryptedData ? [IV => $iv, ENCRYPTEDDATA => $encryptedData] : [];
        $obj = new \service\open\wechatMini\data\Login($openAccount);
        $data = $obj->getSession($code, $userInfo);
        $this->raise($obj->getCode(), $obj->getMsg(), $data);
    }
}
