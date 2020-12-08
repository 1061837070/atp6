<?php
declare (strict_types = 1);

namespace app\back\model;

use think\Model;

/**
 * 后台 功能规则 model
 */
class RuleModel extends Model
{
    protected $name = 'rule';

    /**
     * 查询单条
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

    /**
     * 添加多条
     * @param array $data
     * @return array
     * @throws \Exception
     */
    function addDatas (array $data)
    {
        $res = self::saveAll($data);
        return $res ? $res->toArray() : [];
    }

    /**
     * 查询多条
     * @param array $where
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    function getAllList (array $where = [])
    {
        $list = self::where($where)->order(['id','sort'])->select();
        return $list ? $list->toArray() : [];
    }

}
