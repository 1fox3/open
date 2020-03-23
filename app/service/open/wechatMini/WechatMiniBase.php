<?php
namespace service\open\wechatMini;

use base\Config;

/**
 * 微信小程序基类
 * Class WechatMiniBase
 * @package service\open\wechatMini
 */
class WechatMiniBase
{
    public static $openType = 'wechat_mini';//开放平台类型
    private $wechatMini = '';//微信小程序的标识
    private $appId = '';//公众号的appId
    private $appSecret = '';//公众号的appSecret
    private $token = '';//公众号的token，用于加密验证
    private $openName = '';//公众号名称
    private $config = [];//公众号的config

    /**
     * 构造函数
     * WechatMiniBase constructor.
     * @param $wechatMini
     */
    public function __construct($wechatMini)
    {
        $this->wechatMini = $wechatMini;
        $config = self::getWechatMiniConfig($wechatMini);
        if ($config && is_array($config)) {
            $this->config = $config;
            $this->appId = isset($config['appId']) ? (string)$config['appId'] : '';
            $this->appSecret = isset($config[APPSECRET]) ? (string)$config[APPSECRET] : '';
            $this->token = isset($config[TOKEN]) ? (string)$config[TOKEN] : '';
            $this->openName = isset($config[OPENNAME]) ? (string)$config[OPENNAME] : '';
        }
    }

    /**
     * 获取微信小程序标识
     * @return string
     */
    public function getWechatMini(): string
    {
        return $this->wechatMini;
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
     * 获取微信小程序名称
     * @return string
     */
    public function getOpenName(): string
    {
        return $this->openName;
    }

    /**
     * 获取微信小程序配置信息
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * 获取微信小程序配置
     * @param $wechatMini
     * @return array
     */
    public static function getWechatMiniConfig($wechatMini): array
    {
        $wechatMiniConfig = Config::get('/open/wechatMini', $wechatMini);
        return is_array($wechatMiniConfig) ? $wechatMiniConfig : [];
    }
}
