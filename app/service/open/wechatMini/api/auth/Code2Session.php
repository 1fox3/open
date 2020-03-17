<?php
namespace service\open\wechatMini\api\auth;

use service\open\wechatMini\api\Common;

/**
 * 微信小程序用户登录信息
 * Class Code2Session
 * @package service\open\wechatMini\api\auth
 */
class Code2Session extends Common
{
    //微信小程序接口
    protected $api = '/sns/jscode2session';
    //默认接口请求方式
    protected $requestMethod = 'get';
    //登录时获取的code
    private $jsCode = '';

    /**
     * 获取登录时获取的code
     * @return string
     */
    public function getJsCode(): string
    {
        return $this->jsCode;
    }

    /**
     * 设置登录时获取的code
     * @param string $jsCode
     */
    public function setJsCode(string $jsCode): void
    {
        $this->jsCode = $jsCode;
    }



    /**
     * 返回接口调用参数
     * @return array
     */
    public function getApiParams()
    {
        return [
            GRANT_TYPE => 'authorization_code',
            APPID      => $this->getAppId(),
            SECRET     => $this->getAppSecret(),
            JS_CODE    => $this->getJsCode(),
        ];
    }
}
