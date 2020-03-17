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
                    $handlerName = isset($xml['MsgType']) ? (string)$xml['MsgType'] : '';
                    if ('event' === $handlerName) {
                        $handlerName = isset($xml['Event']) ? (string)$xml['Event'] : '';
                    }

                    $handlerName = ucwords($handlerName);
                    $handler = 'service\\open\\wechat\\handle\\' . $handlerName;

                    if (class_exists($handler)) {
                        $reClass = new \ReflectionClass($handler);
                        $method = 'handle';
                        if ($reClass->hasMethod($method) && $reClass->getMethod($method)->isPublic()) {
                            $obj = new $handler($wechat);
                            $reply = $obj->$method($xml);
                            $passiveReplyObj->exec($reply, $xml);
                        }
                    } else {
                        log_debug('openWechatGatewayXmlNoHandlerInfo', $xml);
                    }
                }
            }
        }
        $passiveReplyObj->exec();
    }

    public function menu()
    {
        $wechat = 'lss';
        $obj = new \service\open\wechat\MenuTool($wechat);
        $menuArr = [
            [
                NAME => '股市数据',
                SUB_BUTTON => [
                    [
                        TYPE => VIEW,
                        NAME => '关注列表',
                        "url" => $obj->getMenuUrl('http://stock.1fox3.com/stock/fllowList.html'),
                    ],
                    [
                        TYPE => VIEW,
                        NAME => '涨跌变化',
                        "url" => $obj->getMenuUrl('http://stock.1fox3.com/stock/upDown.html'),
                    ],
                    [
                        TYPE => VIEW,
                        NAME => '连续涨跌',
                        "url" => $obj->getMenuUrl('http://stock.1fox3.com/stock/limitUpDown.html'),
                    ],
                ],
            ],
        ];

        $menuCreateObj = new \service\open\wechat\api\MenuCreate($wechat);
        $menuCreateObj->setMenu($menuArr);
        $menuCreateObj->exec();
        echo $menuCreateObj->getCode() . $menuCreateObj->getMsg();
    }

    /**
     * 请求测试
     */
    public function test()
    {
        log_debug('test', $_REQUEST);
    }
}
