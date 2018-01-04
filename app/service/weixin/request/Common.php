<?php
namespace service\weixin\request;

use service\weixin\Base;
use base\http;

abstract class Common extends Base{
	//微信公众号接口网关
	private $_gateway = "https://api.weixin.qq.com/cgi-bin";

	//接口调用code
	private $_code = 1;
	//接口调用msg
	private $_msg = "尚未调用微信公众号";

	//微信公众号接口
	protected $_api;

	//确定调用接口
	public function __construct($config){
		parent::__construct($config);

		$calledClass = str_replace(__NAMESPACE__ . "\\","",get_called_class());

		$calledClass = preg_replace('/([A-Z])/','/$1',$calledClass);

		$calledClass = strtolower($calledClass);

		$this->_api = $this->_gateway . $calledClass;
	}

	//获取调用微信公众号接口所需的参数
	abstract function getApiParams();

	/**
	 * 获取access_token
	 * @return string
	 */
	public function accessToken(){
		return "SfYQ4lbIilHyrdDJkjgaJqHBqxs__G8Lr9XPeyqNU-nnCDo_1t8s6nB7wqVvvuncu5ApmKuN5TO6Qd4pkMxVR2STwuO6gui78ieqXbdWAFSRC5vEDHzjEIgBcVpHJ0iTCXChAAAIDI";
		if($this->getAppId() && $this->getAppSecret()){
			return $this->freshAccessToken();
		}

		return "";

	}

	/**
	 * 刷新access_token
	 * @return string
	 */
	public function freshAccessToken(){
		if($this->getAppId() && $this->getAppSecret()){
			$api = $this->_gateway . "/token";
			$params = [
				"grant_type" => "client_credential",
				"appid" => $this->getAppId(),
				"secret" => $this->getAppSecret(),
			];

			$ret = http::get($api,$params);
			if($ret){
				$ret = json_decode($ret,true);
				if(json_last_error() == JSON_ERROR_NONE && !isset($ret["errcode"]) && isset($ret["access_token"])){
					$accessToken = $ret["access_token"];
					$expireTime = $ret["expires_in"];

					return $accessToken;
				}
			}
		}

		return "";
	}

	/**
	 * 调用接口
	 * @return array|mixed|null
	 */
	public function exec(){
		$params = $this->getApiParams();
		$params = $params ? : [];

		$api = $this->_api . "?access_token=" . $this->accessToken() . "&" . http_build_query($params);

		if($params) {
			//注意:参数中可能带有特殊字符或中文乱码之类的数据传递到微信或者QQ服务窗可能报错
			//json_encode时需要JSON_UNESCAPED_UNICODE参数
			$params = json_encode($params, JSON_UNESCAPED_UNICODE);
			//QQ服务窗必须http协议头里面指定json访问(QQ的坑)
			$option = [
				"HTTPHEADER" => [
					'Content-Type: application/json',
				],
			];

			$ret = http::post($api,$params,$option);
		}else{
			$ret = http::get($api);
		}

		$ret = json_decode($ret,true);

		if(json_last_error() == JSON_ERROR_NONE){
			if(!isset($ret["errcode"]) || 0 == $ret["errcode"]){
				$this->_code = 0;
				$this->_msg = "成功";
				return $ret;
			}else{
				$this->_code = $ret["errcode"];
				$this->_msg = $ret["errmsg"];

//				if(40001 == $this->_code || 41001 == $this->_code ){//access_token时效，刷新
//					$this->freshAccessToken();
//				}
				return [];
			}
		}

		$this->_code = 1;
		$this->_msg = "微信接口调用失败";
		return [];
	}

	/**
	 * 获取code
	 * @return int
	 */
	public function getCode(){
		return $this->_code;
	}

	/**
	 * 获取msg
	 * @return string
	 */
	public function getMsg(){
		return $this->_msg;
	}
}
