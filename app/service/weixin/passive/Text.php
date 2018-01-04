<?php
namespace service\weixin\passive;

class Text{
	public function exec($str = "", $xml = [], $isExit = true)
	{
		$str = is_string($str) ? trim($str) : "";

		$toUserName = $xml["FromUserName"];
		$fromUserName = $xml["ToUserName"];

		$resultStr = "success";
		if (!empty($str) && !empty($toUserName) && !empty($fromUserName)) {
			$template = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[%s]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";
			$resultStr = sprintf($template, $toUserName, $fromUserName, time(), "text", $str);
		}

		if ($isExit) {
			exit($resultStr);
		} else {
			return $resultStr;
		}
	}
}
