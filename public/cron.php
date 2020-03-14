<?php
set_time_limit(0);
//定义应用目录
define("APPPATH" , __DIR__ . "/../app/");
//定义项目入口
define("ENTRYPATH" , __DIR__ . "/");
//定义框架目录
define("FRAEMPATH", __DIR__ . "/../../frame/");
//设置当前目录为运行环境
chdir(__DIR__);

require FRAEMPATH . "base/Entry.php";
require 'const.php';

$entry = new \base\Entry();