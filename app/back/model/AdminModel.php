<?php
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
     * 添加
     * @param array $data
     * @return AdminModel|Model
     */
    public function addData(array $data)
    {
        return self::create($data);
    }

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

}
