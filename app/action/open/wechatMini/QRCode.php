<?php

namespace action\open\wechatMini;

use core\Action;
use base\Input;

class QRCode extends Action
{
    public function unlimit()
    {
        $openAccount = Input::any(WECHATMINI, '');
        $obj = new \service\open\wechatMini\api\wxacode\GetUnlimited($openAccount);
        $this->raise($obj->getCode(), $obj->getMsg(), $obj->exec());
    }
}
