<?php
namespace core;

/**
 * controller基类
 * Class Action
 * @package core
 */
class Action {

    public function __construct()
    {
        //保证页面输出的数据是utf8格式的
        header('Content-Type:text/html;charset=UTF-8');
    }

    /**
     * 页面呈现是需要的参数
     * @param array $params
     * @return array
     */
    public function showParams(array $params = [])
    {
        $fixArr = [
            TITLE => __CLASS__,
        ];

        return array_merge($fixArr,$params);
    }
}
