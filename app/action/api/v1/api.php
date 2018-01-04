<?php
namespace action\api\v1;

use core\action;
use base\input;
use base\http;

class api extends action{
	public function __construct(){
		header("Content-Type:text/html;charset=UTF-8");
		parent::__construct();
	}

	public function index(){
		exit("11111123213123ase134asdfqwr1");
	}
}
