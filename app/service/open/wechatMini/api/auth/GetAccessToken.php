<?php
namespace service\open\wechatMini\api\auth;

use service\open\wechatMini\api\Common;
use base\Http;
use base\Cache;

/**
 * 微信小程序接口调用凭证
 * Class GetAccessToken
 * @package service\open\wechatMini\api\auth
 */
class GetAccessToken extends Common
{
    //微信小程序接口
    protected $api = '/cgi-bin/token';
    //默认接口请求方式
    protected $requestMethod = 'get';

    /**
     * 返回接口调用参数
     * @return array
     */
    public function getApiParams()
    {
        return [
            GRANT_TYPE => 'client_credential',
            APPID      => $this->getAppId(),
            SECRET     => $this->getAppSecret(),
        ];
    }

    /**
     * 获取微信公众号的token缓存key
     * @return string
     */
    private function getWechatMiniTokenCacheKey(): string
    {
        return 'openWechatMiniAccessToken:'. $this->getWechatMini();
    }

    /**
     * 获取微信小程序请求接口需要的token
     * @param bool $refresh
     * @return string
     */
    public function accessToken($refresh = false) {
        $accessToken = '';
        if (!$refresh) {
            $accessToken = Cache::get($this->getWechatMiniTokenCacheKey());
            if ($accessToken) {
                return $accessToken;
            }
        }
        if ($this->getAppId() && $this->getAppSecret()) {
            $params = $this->getApiParams();
            $apiUrl = self::$gateway . $this->api;
            $ret = Http::get($apiUrl, $params);
            $ret = json_decode((string)$ret, true);
            if (json_last_error() === JSON_ERROR_NONE && !isset($ret['errcode']) && isset($ret['access_token'])) {
                $accessToken = (string)$ret['access_token'];
                $expireTime = (int)$ret['expires_in'];
                Cache::set($this->getWechatMiniTokenCacheKey(), $accessToken, $expireTime - 100);
            }
            log_error(
                'OpenWechatMiniError',
                [
                    $apiUrl,
                    $params,
                    $ret,
                    Http::getLastRequestCode(),
                    Http::getLastRequestMsg(),
                    Http::getLastRequestInfo()
                ]
            );
        }
        return $accessToken;
    }
}
