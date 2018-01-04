<?php
namespace action;
use core\action;
use base\input;
use base\http;

class home extends action{
	public function __construct(){
		header("Content-Type:text/html;charset=UTF-8");
		parent::__construct();
	}

	public function index(){
	}
}
