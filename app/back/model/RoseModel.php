<?php
/*
 * @Descripttion: 
 */
declare (strict_types = 1);

namespace app\back\model;

use think\Model;

/**
 * Class RoseModel 后台 角色
 * @package app\back\model
 */
class RoseModel extends Model
{
    protected $name = 'rose';

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
     * @msg: 单条查询
     * @param array $where = [] 查询条件，默认为空
     * @return {*}
     */
    public function getInfo(array $where = [])
    {
        $res = self::where($where)->find();
        return $res ? $res->toArray() : [];
    }

}
