<?php
namespace service\open\wechatMini\passive;

use service\open\wechatMini\WechatMiniBase;

/**
 * 被动消息文本类
 * Class Text
 * @package service\open\wechatMini\passive
 */
class Text extends WechatMiniBase
{
    /**
     * 被动消息回复
     * @param string $str
     * @param array $xml
     * @param bool $isExit
     * @return string
     */
    public function exec($str = '', $xml = [], $isExit = true)
    {
        $str = is_string($str) ? trim($str) : '';

        $toUserName = $xml['FromUserName'];
        $fromUserName = $xml['ToUserName'];

        $resultStr = 'success';
        if (!empty($str) && !empty($toUserName) && !empty($fromUserName)) {
            $template = '<xml>' .
                '<ToUserName><![CDATA[%s]]></ToUserName>' .
                '<FromUserName><![CDATA[%s]]></FromUserName>' .
                '<CreateTime>%s</CreateTime>' .
                '<MsgType><![CDATA[%s]]></MsgType>' .
                '<Content><![CDATA[%s]]></Content>' .
                '</xml>';
            $resultStr = sprintf($template, $toUserName, $fromUserName, time(), 'text', $str);
        }

        if ($isExit) {
            exit($resultStr);
        }
        return $resultStr;
    }
}
