<?php
namespace service\open\wechatMini\verify;

use service\open\wechatMini\WechatMiniBase;

/**
 * 签名验证
 * Class Verify
 * @package service\open\wechatMini\verify
 */
class Verify extends WechatMiniBase
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
