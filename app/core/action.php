<?php
namespace core;

class action{
	public function __construct(){
		header("Content-Type:text/html;charset=UTF-8");
	}

	public function showParams($params = []){
		$fixArr = [
			"title" => __CLASS__,
		];

		return is_array($params) ? array_merge($fixArr,$params) : $fixArr;
	}
}
