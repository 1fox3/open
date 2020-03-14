<?php
namespace service\open\wechat;

class MenuTool extends WechatBase
{
    /**
     * 菜单链接
     * @var string
     */
    private static $menuBaseUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';

    public function getMenuUrl($url, $params = [])
    {
        $baseParams = [
            APPID         => $this->getAppId(),
            REDIRECT_URI  => urlencode($url),
            RESPONSE_TYPE => CODE,
            SCOPE         => 'snsapi_userinfo',
            STATE         => $this->getWechat(),
        ];
        $params = array_merge($baseParams, $params);
        return static::$menuBaseUrl . '?' . http_build_query($params) . '#wechat_redirect';
    }
}
