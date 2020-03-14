<?php
namespace service\open\wechat\handle;

use service\open\wechat\WechatBase;

/**
 * 取消关注事件
 * Class Unsubscribe
 * @package service\open\wechat\handle
 */
class Unsubscribe extends WechatBase
{
    public function handle($xmlArr): string
    {
        $openid = isset($xmlArr['FromUserName']) ? (string)$xmlArr['FromUserName'] : '';
        $unfollowTimestamp = isset($xmlArr['CreateTime']) ? (int)$xmlArr['CreateTime'] : time();
        $tOpenUserInfoObj = new \model\User\TOpenUserInfo();

        $where = [
            OPENID => $openid,
            OPEN_TYPE => self::$openType,
            OPEN_ACCOUNT => $this->getWechat(),
        ];

        $updateInfo = [
            FOLLOW_STATUS => 0,
            UNFOLLOW_TIME   => date('Y-m-d H:i:s', $unfollowTimestamp),
        ];

        if ($tOpenUserInfoObj->getOne($where)) {
            $tOpenUserInfoObj->update($where, $updateInfo);
        } else {
            $tOpenUserInfoObj->insert(array_merge($where, $updateInfo));
        }

        return '';
    }
}
