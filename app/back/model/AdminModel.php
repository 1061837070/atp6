<?php
declare (strict_types = 1);

namespace app\back\model;

use think\Model;

/**
 * 后台 管理员 model
 */
class AdminModel extends Model
{
    protected $name = 'admin';

    /**
     * [addData 添加]
     * @param [type] $data [description]
     */
    public function addData($data)
    {
        return $this->insert($data);
    }

    /**
     * [updataData 修改]
     * @param  [type] $id   [description]
     * @param  [type] $data [description]
     * @return [type]       [description]
     */
    public function updateData($id, $data)
    {
        return $this->where('id', $id)->update($data);
    }

    /**
     * [delData 删除]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delData($id)
    {
        return $this->where('id', $id)->delete();
    }

    /**
     * [getList 查询多条]
     * @param  array  $where [description]
     * @return [type]        [description]
     */
    public function getList($where = [])
    {
        $res = $this
            ->alias('a')
            ->join('rose r', 'a.rose_id = r.id')
            ->where($where)
            ->field('a.*,r.name as rosename')
            ->select();
        return $res ? $res->toArray() : [];
    }

    /**
     * [getInfo 查询单条]
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    public function getInfo($where)
    {
        $res = $this->where($where)->find();
        return $res ? $res->toArray() : [];
    }

    /**
     * [checkExit 除指定id，是否还有符合指定查询条件的功能分类]
     * @param  [type] $where [description]
     * @param  [type] $id    [description]
     * @return [type]        [description]
     */
    public function checkExit($where, $id)
    {
        $res = $this
            ->where('id', '<>', $id)
            ->where($where)
            ->find();
        return empty($res) ? false : true;
    }

}
