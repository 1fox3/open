<?php

namespace service\open\wechat\api;

/**
 * 创建微信公众号菜单
 * Class MenuCreate
 * @package service\open\wechat\api
 */
class MenuCreate extends Common
{
    private $menu = []; //菜单

    /**
     * 设置菜单
     * @param $menu
     */
    public function setMenu($menu)
    {
        if (!empty($menu) && is_array($menu)) {
            $this->menu = $menu;
        }
    }

    /**
     * 返回接口调用参数
     * @return array
     */
    public function getApiParams()
    {
        return [
            'button' => $this->menu,
        ];
    }
}
