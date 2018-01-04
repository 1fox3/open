<?php
namespace service\weixin;

class Base{
	private $_appId;		//公众号的appId
	private $_appSecret;	//公众号的appSecret
	private $_token;		//公众号的token，用于加密验证
	private $_config;		//公众号的config

	public function __construct($config){
		if(!empty($config) && is_array($config)){
			$this->_config = $config;

			if(isset($config["appId"]) && !empty($config["appId"])){
				$this->_appId = $config["appId"];
			}

			if(isset($config["appSecret"]) && !empty($config["appSecret"])){
				$this->_appSecret = $config["appSecret"];
			}

			if(isset($config["token"]) && !empty($config["token"])){
				$this->_token = $config["token"];
			}
		}
	}

	public function getAppId(){
		return $this->_appId ? : "";
	}

	public function getAppSecret(){
		return $this->_appSecret ? : "";
	}

	public function getToken(){
		return $this->_token ? : "";
	}

	public function getConfig(){
		return $this->_config ? : [];
	}

	/**
	 * 接受消息验证
	 * @param $signature
	 * @param $timestamp
	 * @param $nonce
	 * @return bool
	 */
	public function msgVerify($signature,$timestamp,$nonce){
		if(empty($signature) || !is_string($signature)
			|| empty($timestamp) || !is_string($timestamp) || !preg_match('/^\d+$/',$timestamp)
			|| empty($nonce) || !is_string($nonce)
		){
			return false;
		}
		$signArr = [$this->_token,$timestamp,$nonce];
		sort($signArr, SORT_STRING);
		$signStr = implode($signArr);
		$sign = sha1($signStr);

		if($sign == $signature){
			return true;
		}

		return false;
	}
}
