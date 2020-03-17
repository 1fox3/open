<?php
namespace service\open\wechatMini\api;

use service\open\wechatMini\api\auth\GetAccessToken;
use service\open\wechatMini\WechatMiniBase;
use base\Http;

abstract class Common extends WechatMiniBase
{
    //微信小程序接口域名
    protected static $gateway = 'https://api.weixin.qq.com';
    //接口调用code
    private $code = 1;
    //接口调用msg
    private $msg = '尚未调用微信小程序接口';
    //微信小程序接口
    protected $api;
    //默认接口请求方式
    protected $requestMethod = 'post';

    /**
     * 错误码
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * 错误信息
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * 获取调用微信公众号接口所需的参数
     * @return mixed
     */
    abstract function getApiParams();

    /**
     * 获取access_token
     * @return string
     */
    public function accessToken($refeash = false)
    {
        $tokenObj = new GetAccessToken($this->getWechatMini());
        return $tokenObj->accessToken($refeash);
    }

    /**
     * 调用接口
     * @return array|mixed|null
     */
    public function exec()
    {
        $params = $this->getApiParams();
        $params = $params ?: [];

        $apiUrl = self::$gateway . $this->api . '?access_token=' . $this->accessToken();

        if ($params && 'post' === $this->requestMethod) {
            //json_encode时需要JSON_UNESCAPED_UNICODE参数
            $params = json_encode($params, JSON_UNESCAPED_UNICODE);
            //QQ服务窗必须http协议头里面指定json访问(QQ的坑)
            $option = [
                'HTTPHEADER' => [
                    'Content-Type: application/json',
                ],
            ];

            $ret = Http::post($apiUrl, $params, $option);
        } else {
            $ret = Http::get($apiUrl, $params);
        }

        if (0 !== Http::getLastRequestCode()) {
            $this->code = Http::getLastRequestCode();
            $this->msg = Http::getLastRequestMsg();
            return [];
        }

        $ret = json_decode((string)$ret, true);

        if (json_last_error() == JSON_ERROR_NONE && is_array($ret)) {
            if (!isset($ret['errcode']) || 0 === (int)$ret['errcode']) {
                $this->code = 0;
                $this->msg = '成功';
            } else {
                $this->code = isset($ret['errcode']) ? (int)$ret['errcode'] : 1;
                $this->msg =  isset($ret['errmsg']) ? (string)$ret['errmsg'] : '微信接口请求失败';
            }
        } else {
            $this->code = 1;
            $this->msg = '微信接口返回数据格式错误';
            $ret = [];
        }

        if (0 !== $this->getCode()) {
            log_error(
                'OpenWechatError',
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

        if(40001 == $this->code || 41001 == $this->code ){//token时效，刷新
            $this->accessToken(true);
            return $this->exec();
        }
        return $ret;
    }
}
