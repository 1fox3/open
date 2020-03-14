<?php
namespace action\open;

use core\Action;
use base\Input;

class Gateway extends Action
{
    /**
     * 微信公众号网关地址
     */
    public function wechat($wechat)
    {
        //微信加密签名
        $signature = Input::get('signature');
        //时间戳
        $timestamp = Input::get('timestamp');
        //随机数
        $nonce = Input::get('nonce');
        $verifyObj = new \service\open\wechat\verify\Verify($wechat);
        $passiveReplyObj = new \service\open\wechat\passive\Text($wechat);

        if ($verifyObj->msgVerify($signature, $timestamp, $nonce)) {//签名验证通过
            $xml = file_get_contents('php://input');//xml格式的消息
            //微信公众号首次验证。,
            $echoStr = Input::get('echostr');

            if (empty($xml) && $echoStr && is_string($echoStr)) {
                echo $echoStr;
                exit(0);
            }

            if ($xml) {
                $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
                $xml = json_decode(json_encode($xml), true);

                if ($xml && is_array($xml)) {
                    log_debug('wechatGatewayXmlInfo', $xml);
                }
            }
        }
        $passiveReplyObj->exec();
    }
}
