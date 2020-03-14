<?php
/**
 * 路由重写规则配置
 */
return [
    '/open/gateway/wechat/(.*)' => '/open/Gateway/wechat:wehat=$1',
];