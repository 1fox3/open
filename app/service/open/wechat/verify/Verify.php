<?php

namespace service\open\wechat\verify;

use service\open\wechat\WechatBase;

class Verify extends WechatBase
{
    /**
     * 接受消息验证
     * @param $signature
     * @param $timestamp
     * @param $nonce
     * @return bool
     */
    public function msgVerify($signature, $timestamp, $nonce)
    {
        if (empty($signature) || !is_string($signature)
            || empty($timestamp) || !is_string($timestamp) || !preg_match('/^\d+$/', $timestamp)
            || empty($nonce) || !is_string($nonce)
        ) {
            return false;
        }
        $signArr = [$this->getToken(), $timestamp, $nonce];
        sort($signArr, SORT_STRING);
        $signStr = implode($signArr);
        $sign = sha1($signStr);

        return $sign === $signature;
    }
}
