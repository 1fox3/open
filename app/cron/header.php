<?php
require __DIR__ . '/../../public/cron.php';

$obj = new \service\open\wechatMini\api\auth\GetPaidUnionId('1fox3');
$obj->setOpenid('o3c7q0O2YzATGOxBwVjJ6yz2H5WY');
print_r($obj->exec());