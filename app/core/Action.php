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

    /**
     * 接口类返回
     * @param $code
     * @param $msg
     * @param string $data
     */
    protected function raise($code, $msg, $data = '')
    {
        header('Content-Type:application/json;charset=UTF-8');
        exit(json_encode([CODE => $code, MSG => $msg, DATA => $data]));
    }
}
