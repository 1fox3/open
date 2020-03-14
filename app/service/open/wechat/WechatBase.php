<?php

namespace service\open\wechat;

use base\Config;

class WechatBase
{
    public static $openType = 'wechat';//开放平台类型
    private $wechat = '';//公众号的标识
    private $appId = '';//公众号的appId
    private $appSecret = '';//公众号的appSecret
    private $token = '';//公众号的token，用于加密验证
    private $openName = '';//公众号名称
    private $config = [];//公众号的config

    public function __construct($wechat)
    {
        $this->wechat = $wechat;
        $config = self::getWechatConfig($wechat);
        if ($config && is_array($config)) {
            $this->config = $config;
            $this->appId = isset($config[APP_ID]) ? (string)$config[APP_ID] : '';
            $this->appSecret = isset($config[APP_SECRET]) ? (string)$config[APP_SECRET] : '';
            $this->token = isset($config[TOKEN]) ? (string)$config[TOKEN] : '';
            $this->openName = isset($config[OPENNAME]) ? (string)$config[OPENNAME] : '';
        }
    }

    /**
     * 获取公众号标识
     * @return string
     */
    public function getWechat(): string
    {
        return $this->wechat;
    }

    /**
     * 获取appId
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * 获取appSecret
     * @return string
     */
    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    /**
     * 获取token
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * 获取公众号名称
     * @return string
     */
    public function getOpenName(): string
    {
        return $this->openName;
    }



    /**
     * 获取微信公众号配置信息
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * 获取公众号配置
     * @param $wechat
     * @return array
     */
    public static function getWechatConfig($wechat): array
    {
        $wechatConfig = Config::get('/open/wechat', $wechat);
        return is_array($wechatConfig) ? $wechatConfig : [];
    }
}
