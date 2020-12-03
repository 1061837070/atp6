<?php
declare (strict_types = 1);

namespace app\back\model;

use think\Model;

/**
 * 后台 角色 模型
 */
class RoseModel extends Model
{
    protected $name = 'rose';

    /**
     * [addData 添加]
     * @param [array] $data [description]
     */
    public function addData(array $data)
    {
        return $this->insert($data);
    }

    /**
     * [getInfo 查询单条]
     * @param  [array] $where [description]
     * @return [type]         [description]
     */
    public function getInfo(array $where = [])
    {
        $res = $this->where($where)->find();
        return $res ? $res->toArray() : [];
    }

}
