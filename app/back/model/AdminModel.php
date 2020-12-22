<?php
/*
 * @Descripttion: 
 */
declare (strict_types = 1);

namespace app\back\model;

use think\Model;

/**
 * Class AdminModel 后台 管理员账号
 * @package app\back\model
 */
class AdminModel extends Model
{
    protected $name = 'admin';

    /**
     * @msg: 添加单条
     * @param array $data 添加的数据
     * @return {*}
     */
    public function addData(array $data)
    {
        return self::create($data);
    }

    /**
     * @msg: 查询单条
     * @param array $where = [] 查询条件 默认空
     * @return {*}
     */
    public function getInfo(array $where = [])
    {
        $res = self::where($where)->find();
        return $res ? $res->toArray() : [];
    }

}
