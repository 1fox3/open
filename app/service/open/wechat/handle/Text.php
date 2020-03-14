<?php
namespace service\open\wechat\handle;

use service\open\wechat\WechatBase;

/**
 * 文本消息
 * Class Text
 * @package service\open\wechat\handle
 */
class Text extends WechatBase
{
    public function handle($xmlArr): string
    {
        $openid = isset($xmlArr['FromUserName']) ? (string)$xmlArr['FromUserName'] : '';
        $inputContent = isset($xmlArr['Content']) ? (string)$xmlArr['Content'] : '';
        return '你为什么对我说这些：' . $inputContent;
    }
}
