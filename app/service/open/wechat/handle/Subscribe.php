<?php
namespace service\open\wechat\handle;

use service\open\wechat\WechatBase;

/**
 * 关注事件
 * Class Subscribe
 * @package service\open\wechat\handle
 */
class Subscribe extends WechatBase
{
    public function handle($xmlArr): string
    {
        $openid = isset($xmlArr['FromUserName']) ? (string)$xmlArr['FromUserName'] : '';
        $followTimestamp = isset($xmlArr['CreateTime']) ? (int)$xmlArr['CreateTime'] : time();
        $tOpenUserInfoObj = new \model\User\TOpenUserInfo();

        $where = [
            INT_OPENID   => crc32($openid),
            OPENID       => $openid,
            OPEN_TYPE    => self::$openType,
            OPEN_ACCOUNT => $this->getWechat(),
        ];

        $update = [
            FOLLOW_STATUS => 1,
            FOLLOW_TIME   => date('Y-m-d H:i:s', $followTimestamp),
        ];

        $row = $tOpenUserInfoObj->getOne($where);
        if (is_array($row) && $row) {
            $tOpenUserInfoObj->update([ID => $row[ID]], $update);
        } else {
            $tOpenUserInfoObj->insert(array_merge($where, $update));
        }

        return '欢迎关注' . $this->getOpenName() . '微信公众号';
    }
}
