<?php
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
     * 添加
     * @param array $data
     * @return RoseModel|Model
     */
    public function addData(array $data)
    {
        return self::create($data);
    }

    /**
     * 单条查询
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfo(array $where = [])
    {
        $res = self::where($where)->find();
        return $res ? $res->toArray() : [];
    }

}
