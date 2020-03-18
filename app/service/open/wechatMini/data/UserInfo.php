<?php
namespace service\open\wechatMini\data;

use service\open\wechatMini\decrypt\DecryptUserInfo;

/**
 * 用户信息
 * Class UserInfo
 * @package service\open\wechatMini\data
 */
class UserInfo extends DataBase
{
    /**
     * 保存用户信息
     * @param string $sessionKey
     * @param string $iv
     * @param string $encryptedData
     * @return bool|mixed|null
     */
    public function saveUserInfo(string $sessionKey, string $iv, string $encryptedData)
    {
        $userInfo = DecryptUserInfo::decrypt($sessionKey, $iv, $encryptedData);
        if (empty($userInfo)) {
            return $this->reset(9001, '用户信息解码失败', 0);
        }
        $openid = isset($userInfo['openId']) ? (string)$userInfo['openId'] : '';
        $nickname = isset($userInfo['nickName']) ? (string)$userInfo['nickName'] : '';
        $where = [
            OPEN_TYPE    => self::$openType,
            OPEN_ACCOUNT => $this->getWechatMini(),
            OPENID       => $openid,
            INT_OPENID   => crc32($openid),
        ];

        $update = [
            NICKNAME     => base64_encode($nickname),
            GENDER       => isset($userInfo['gender']) ? (int)$userInfo['gender'] : 0,
            COUNTRY      => isset($userInfo[COUNTRY]) ? (string)$userInfo[COUNTRY] : '',
            PROVINCE     => isset($userInfo[PROVINCE]) ? (string)$userInfo[PROVINCE] : '',
            CITY         => isset($userInfo[CITY]) ? (string)$userInfo[CITY] : '',
            LANGUAGE     => isset($userInfo[LANGUAGE]) ? (string)$userInfo[LANGUAGE] : '',
            HEAD_IMG     => isset($userInfo['avatarUrl']) ? (string)$userInfo['avatarUrl'] : '',
        ];

        $tOpenUserInfoObj = new \model\User\TOpenUserInfo();
        $row = $tOpenUserInfoObj->getOne($where);
        if (is_array($row) && $row) {
            $tOpenUserInfoObj->update([ID => $row[ID]], $update);
            return $row[ID];
        }
        $insertId = $tOpenUserInfoObj->insert(array_merge($where, $update));
        $tUserObj = new \model\User\TUser();
        $tUserObj->insert([
            PLAT_TYPE    => 1,
            PLAT_ID      => $this->getWechatMini(),
            PLAT_USER_ID => $openid,
        ]);
        return $insertId;
    }
}
