<?php
namespace service\open\wechatMini\api\wxacode;

use service\open\wechatMini\api\Common;
use base\Cache;

/**
 * 获取小程序临时二维码
 * Class GetUnlimited
 * @package service\open\wechatMini\api\wxacode
 */
class GetUnlimited extends Common
{
    //微信小程序接口
    protected $api = '/wxa/getwxacodeunlimit';
    //默认接口请求方式
    protected $requestMethod = POST;
    //接口返回的数据格式
    protected $responseType = TEXT;
    //二维码场景值
    private $scene = 0;

    /**
     * 获取场景值
     * @return int
     */
    public function getScene(): int
    {
        if (empty($this->scene)) {
            $cacheKey = $this->getWechaMiniUnlimitCacheSceneKey();
            $this->scene = Cache::incr($cacheKey);
            if (is_numeric($this->scene) && $this->scene > 1000000) {
                Cache::del($cacheKey);
            }
        }
        return $this->scene;
    }

    private function getWechaMiniUnlimitCacheSceneKey(): string
    {
        return 'WechatMiniUnlimitCacheSceneKey:' . $this->getWechatMini();
    }

    /**
     * 返回接口调用参数
     * @return array
     */
    public function getApiParams()
    {
        return [
            SCENE => $this->getScene(),
        ];
    }

    public function exec()
    {
        $ret = parent::exec();
        $file = 'D:\mbaLog\a.jpg';
        file_put_contents($file, $ret);
        return [];
    }
}
