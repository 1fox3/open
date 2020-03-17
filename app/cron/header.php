<?php
require __DIR__ . '/../../public/cron.php';

$jsonStr = '{"\/open\/Gateway\/test":"","code":"021m0gif0Umhhw1LTwjf0w9sif0m0gis","userInfo":"{\"nickName\":\"\u677e\u677e\",\"gender\":1,\"language\":\"zh_CN\",\"city\":\"\",\"province\":\"\",\"country\":\"China\",\"avatarUrl\":\"https:\/\/wx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJ25iaO2lTHibMRNsy0ZtNHlJPicqk6ib3yX6Brxga43PvBjurGF90D1X10L7DMfSCg8rcTOxWa7upCMQ\/132\"}","nickName":"\u677e\u677e","avatarUrl":"https:\/\/wx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJ25iaO2lTHibMRNsy0ZtNHlJPicqk6ib3yX6Brxga43PvBjurGF90D1X10L7DMfSCg8rcTOxWa7upCMQ\/132","gender":"1","province":"","city":"","country":"China","rawData":"{\"nickName\":\"\u677e\u677e\",\"gender\":1,\"language\":\"zh_CN\",\"city\":\"\",\"province\":\"\",\"country\":\"China\",\"avatarUrl\":\"https:\/\/wx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTJ25iaO2lTHibMRNsy0ZtNHlJPicqk6ib3yX6Brxga43PvBjurGF90D1X10L7DMfSCg8rcTOxWa7upCMQ\/132\"}","signature":"27f7502aa7eb2d9448f652d18e53c2f20f416d52","encryptedData":"cYIe\/\/SZJgDX7JamEa9kdRbHLiVm+zORJkXCp0GBXwhpXhKtZLJLQhzB2CjVh342emQbCQME6EFsDQCAFVvGba9n84RdEvv62qXGlRFwiI6zfHJwT9jtECHtcMQRN+CNtY9c+jZ8th4eBf9Uh2i5QObbGU4NJaWvmZwYi8RCdmv+IgFmDjmvACfOfwl9iXQK5ZeQbTkuKcJXgJjl1bfjP5BYQtKYAMB39LFrWp2KAWOAx6DmwXJgnTAy4DZjUdbLGi8Qkqy3pETdgh4FmGj8FXKUA+2bWbcITGk0\/gb3HRmsE1jlPXeAF0k8v+MHodHqHUjP6wSLv36kvbVeTj2OQbEUzsjdQYQt69H6Udun0n0bER2sALF8GMR3ga9+G\/INC50n3DVVv23ewrk0hjR6T\/ltVeg\/Mz2UKv3Jqy7UrEvMW28uVHUOR\/LywRqpKqrcmOESRopJjY3kw8gp\/8htlw==","iv":"H6cdxCow9gPJ0A8RtOyTTw==","cloudID":"undefined"}';
$jsonArr = json_decode($jsonStr, true);
$code = $jsonArr['code'];
$rawData = $jsonArr['rawData'];
$signature = $jsonArr['signature'];
$encryptedData = $jsonArr['encryptedData'];
$iv = $jsonArr['iv'];
$obj = new \service\open\wechatMini\api\auth\Code2Session('1fox3');
$obj->setJsCode($code);
$ret = $obj->exec();
$sessionKey = $ret['session_key'];
$userInfo = \service\open\wechatMini\decrypt\DecryptUserInfo::verifyAndDecrypt($signature, $sessionKey, $rawData, $iv, $encryptedData);
print_r($ret);
print_r($userInfo);