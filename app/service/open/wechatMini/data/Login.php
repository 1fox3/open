<?php
namespace service\open\wechatMini\data;

use service\open\wechatMini\api\auth\Code2Session;


/**
 * 获取登录信息
 * Class Login
 * @package service\open\wechatMini\data
 */
class Login extends DataBase
{
    /**
     * 获取登录的session
     * @param $code
     * @param array $userInfo
     * @return null
     */
    public function getSession($code, $userInfo = [])
    {
        $code2SessionObj = new Code2Session($this->getWechatMini());
        $code2SessionObj->setJsCode($code);
        $sessionInfo = $code2SessionObj->exec();
        if (!is_array($sessionInfo) || empty($sessionInfo)) {
            return $this->reset($code2SessionObj->getCode(), $code2SessionObj->getMsg(), []);
        }
        $sessionKey = isset($sessionInfo[SESSION_KEY]) ? (string)$sessionInfo[SESSION_KEY] : '';
        if ($sessionKey && $userInfo && isset($userInfo[IV]) && isset($userInfo[ENCRYPTEDDATA])) {
            $userInfoObj = new UserInfo($this->getWechatMini());
            $userInfoObj->saveUserInfo($sessionKey, $userInfo[IV], $userInfo[ENCRYPTEDDATA]);
        }
        return $this->reset(0, '成功', $sessionInfo);
    }
}
