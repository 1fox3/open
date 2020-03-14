<?php
namespace service\open\wechat\api;

use base\Http;
use base\Cache;

class Token extends Common
{
    /**
     * 获取接口需要的参数
     * @return array
     */
    public function getApiParams()
    {
        return [
            'grant_type' => 'client_credential',
            'appid'      => $this->getAppId(),
            'secret'     => $this->getAppSecret(),
        ];
    }

    /**
     * 获取微信公众号的token缓存key
     * @return string
     */
    private function getWechatTokenCacheKey(): string
    {
        return 'openWechatAccessToken'. $this->getWechat();
    }

    /**
     * 获取微信公众号请求接口需要的token
     * @param bool $refresh
     * @return string
     */
    public function accessToken($refresh = false) {
        $accessToken = '';
        if (!$refresh) {
            $accessToken = Cache::get($this->getWechatTokenCacheKey());
            if ($accessToken) {
                return $accessToken;
            }
        }
        if ($this->getAppId() && $this->getAppSecret()) {
            $params = $this->getApiParams();
            $ret = Http::get($this->api, $params);
            $ret = json_decode((string)$ret, true);
            if (json_last_error() === JSON_ERROR_NONE && !isset($ret['errcode']) && isset($ret['access_token'])) {
                $accessToken = (string)$ret['access_token'];
                $expireTime = (int)$ret['expires_in'];
                Cache::set($this->getWechatTokenCacheKey(), $accessToken, $expireTime - 100);
            }
            log_error(
                'OpenWechatError',
                [
                    $this->api,
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
