<?php
namespace action;

use base\cache;
use base\queue;
use base\config;
use core\action;
use base\input;
use base\db;

class test extends action{
	public function __construct(){
		header("Content-Type:text/html;charset=UTF-8");
		parent::__construct();
	}

	public function index(){
		$key = "lusongsong";
		$value = "1";
//		$value = ["a","b"];
		if(queue::push($key,$value)){
			echo "push success";
			echo "<br>";
		}else{
			echo "push fail";
			echo "<br>";
		}

		echo queue::len($key);
		echo "<br>";

		if(queue::rPush($key,$value)){
			echo "push success";
			echo "<br>";
		}else{
			echo "push fail";
			echo "<br>";
		}

		echo queue::len($key);
		echo "<br>";

		print_r(queue::lPop($key));
		echo "<br>";
		print_r(queue::lPop($key));
		echo "<br>";
		exit();
	}
}
