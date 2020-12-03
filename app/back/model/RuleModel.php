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
     * [addData 添加]
     * @param [type] $data [description]
     */
    public function addData($data)
    {
        return $this->insert($data);
    }

    /**
     * [updataData 修改]
     * @param  [type] $where  [description]
     * @param  [type] $data   [description]
     * @return [type]         [description]
     */
    public function updateData($where, $data)
    {
        return $this->where($where)->update($data);
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
     * [getAllList 获取所有功能分类的二维数组]
     * @param  array  $where [查询条件]
     * @param  array  $field [查询的字段]
     * @return [type]        [description]
     */
    public function getAllList($where = [], $field = [])
    {
        $res = $this
            ->where($where)
            ->order('sort asc,id asc')
            ->field($field)
            ->select();
        return $res ? $res->toArray() : [];
    }

    /**
     * [getForTreeList 树形结构的数据]
     * @param  array  $where [description]
     * @return [type]        [description]
     */
    public function getForTreeList($where = [])
    {
        $res = $this
            ->where($where)
            ->order('sort asc,id asc')
            ->field(['id', 'name' => 'title', 'pid'])
            ->select();
        return $res ? $res->toArray() : [];
    }

    /**
     * [getInfo 查询单套]
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
