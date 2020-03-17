<?php
namespace model;

abstract class Base
{
    //数据库链接
    private $conn;

    public function getDB() {
        $class = get_called_class();
        $class = str_replace('\\', '/', (string)$class);
        $pos = strripos($class, '/');
        return $this->nameChange(substr($class, 0, $pos));
    }
    public function getTable() {
        return $this->nameChange(get_called_class());
    }

    /*
     * 名称变换
     */
    private function nameChange($class = '') {
        $class = str_replace('\\', '/', (string)$class);
        $classArr = explode('/', $class);
        $nameStr = array_pop($classArr);
        $nameStr = preg_replace('/([A-Z]{1})/', '_$1', $nameStr);
        $nameStr = strtolower($nameStr);
        return ltrim($nameStr, '_');
    }

    //获取数据库链接
    private function getConn() {
        if (empty($this->conn)) {
            $this->conn = \base\DB::connect($this->getDB());
        }
        return $this->conn->table($this->getTable());
    }

    //查询多条记录
    public function getAll($where = [], $sqlInfo = []) {
        return $this->getConn()->getAll($where, $sqlInfo);
    }

    //插入记录
    public function insert($info) {
        if (!empty($info) && is_array($info)) {
            return $this->getConn()->insert($info);
        }
        return false;
    }

    //获取单条记录
    public function getOne($where = [], $sqlInfo = []) {
        return $this->getConn()->getOne($where,$sqlInfo);
    }

    //更新记录
    public function update($where,$update) {
        return $this->getConn()->update($where,$update);
    }

    //统计数量
    public function count($where) {
        return $this->getConn()->count($where);
    }

    //删除记录
    public function del($where) {
        return $this->getConn()->delete($where);
    }

    //获取查询的sql
    public function getLastQuery() {
        return $this->getConn()->getLastQuery();
    }
}
