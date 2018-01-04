<?php
namespace action;

use base\config;
use core\action;
use base\input;
use base\http;

class gateway extends action{
	public function __construct(){
		header("Content-Type:text/html;charset=UTF-8");
		parent::__construct();
	}

	/**
	 * 微信公众号网关地址
	 */
	public function index(){
		//微信加密签名
		$signature = input::get('signature');
		//时间戳
		$timestamp = input::get('timestamp');
		//随机数
		$nonce = input::get('nonce');

		$passiveReplyObj = new \service\weixin\passive\Text();
		$config = config::get("open");
		if(empty($config)){
			$passiveReplyObj->exec();
		}
		$baseObj = new \service\weixin\Base($config);

		if($baseObj->msgVerify($signature,$timestamp,$nonce)) {//签名验证通过
			$xml = $GLOBALS["HTTP_RAW_POST_DATA"];//xml格式的消息

			//微信公众号首次验证。,
			$echoStr = input::get("echostr");

			if (empty($xml) && !empty($echoStr) && is_string($echoStr)) {
				echo $echoStr;
				exit(0);
			}

			if (!empty($xml)) {
				$xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
				$xml = json_decode(json_encode($xml), true);

				if (!empty($xml) && is_array($xml)) {

					$handlerName = $xml["MsgType"];
					if ("event" == $handlerName) {
						$handlerName = $xml["Event"];
					}

					$handlerName = ucwords($handlerName);
					$handler = 'service\\weixin\\handle\\shop\\' . $handlerName;

					if(class_exists($handler)){
						$reClass = new \ReflectionClass($handler);
						$method = "handle";
						if ($reClass->hasMethod($method) && $reClass->getMethod($method)->isPublic()) {
							$obj = new $handler($config);
							$reply = $obj->$method($xml);

							$passiveReplyObj->exec($reply, $xml);
						}
					}
				}
			}
		}

		//退出
		$passiveReplyObj->exec();
		exit();
	}

	/**
	 * 公众号菜单管理
	 */
	public function menu(){
		$action = input::any("action");
		$config = config::get("open");
		if(empty($config)){
			exit(json_encode(["code" => 1, "msg" => "获取公众号配置失败"]));
		}

		if($action && is_string($action)){
			switch($action){
				case "delete":
					$obj = new \service\weixin\request\MenuDelete($config);
					$obj->exec();
					exit(json_encode(["code" => $obj->getCode(),"msg" => $obj->getMsg()]));
				case "get":
					$obj = new \service\weixin\request\MenuGet($config);
					$ret = $obj->exec();
					if($ret){
						exit(json_encode(["code" => 0,"msg" => "成功","data" => $ret]));
					}else{
						exit(json_encode(["code" => $obj->getCode(),"msg" => $obj->getMsg()]));
					}
					break;
				case "create":
					$obj = new \service\weixin\request\MenuCreate($config);
					$menu = [
						[
							"type" => "view",
							"name" => "查快递",
							"url" => "http://m.kuaidihelp.com/express/open",
						],
						[
							"type" => "view",
							"name" => "发快递",
							"url" => "http://m.kuaidihelp.com/order/index",//发快递暂时先寄给网点
						],
						[
							"type" => "view",
							"name" => "我的订单",
							"url" => "http://m.kuaidihelp.com/order/history",
						],
					];
					$obj->setMenu($menu);
					$obj->exec();
					exit(json_encode(["code" => $obj->getCode(),"msg" => $obj->getMsg()]));
					break;
			}
		}
	}
}
