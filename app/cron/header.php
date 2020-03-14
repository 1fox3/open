<?php
require __DIR__ . '/../../public/cron.php';

$obj = new \service\open\wechat\MenuTool('lss');
echo $obj->getMenuUrl('http://stock.1fox3.com');