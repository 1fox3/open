<?php
namespace service\open\wechatMini\data;

use service\open\wechatMini\WechatMiniBase;

class DataBase extends WechatMiniBase
{
    //接口调用code
    private $code = 1;
    //接口调用msg
    private $msg = '尚未调用微信小程序接口';

    /**
     * 错误码
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * 错误信息
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * 设置错误信息
     * @param $code
     * @param $msg
     * @param null $data
     * @return null
     */
    protected function reset($code, $msg, $data = null)
    {
        $this->code = $code;
        $this->msg = $msg;
        return $data;
    }
}
