<?php
namespace service\open\wechatMini\decrypt;

/**
 * 解密用户信息
 * Class DecryptUserInfo
 * @package service\open\wechatMini\api
 */
class DecryptUserInfo
{
    /**
     * 验证加解密
     * @param string $signature
     * @param string $sessionKey
     * @param string $rawData
     * @param string $iv
     * @param string $encryptedData
     * @return array
     */
    public static function verifyAndDecrypt(
        string $signature,
        string $sessionKey,
        string $rawData,
        string $iv,
        string $encryptedData
    ): array
    {
        if (self::verifySign($signature, $sessionKey, $rawData)) {
            return self::decrypt($sessionKey, $iv, $encryptedData);
        }
        return [];
    }

    /**
     * 解密用户数据
     * @param string $sessionKey
     * @param string $iv
     * @param string $encryptedData
     * @return array
     */
    public static function decrypt(string $sessionKey, string $iv, string $encryptedData): array
    {
        $aesKey = base64_decode($sessionKey);
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $userInfoStr = openssl_decrypt( $aesCipher, 'AES-128-CBC', $aesKey, 1, $aesIV);
        $userInfoArr = json_decode($userInfoStr,true);
        //处理因为昵称导致的json解码失败
        if (json_last_error() !== JSON_ERROR_NONE
            && preg_match('/nickName":"(.*)","gender":[\d]+/', $userInfoStr, $match)
            && isset($match[1])
        ) {
            $nickname = $match[1];
            $userInfoStr = str_replace($nickname, '', $userInfoStr);
            $userInfoArr = json_decode($userInfoStr, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($userInfoArr['nickName'])) {
                $userInfoArr['nickName'] = $nickname;
            }
        }
        return is_array($userInfoArr) ? $userInfoArr : [];
    }

    /**
     * 验证签名是否正确
     * @param string $signature
     * @param string $sessionKey
     * @param string $rawData
     * @return bool
     */
    public static function verifySign(string $signature, string $sessionKey, string $rawData)
    {
        return $signature === sha1($rawData . (string)$sessionKey);
    }
}
