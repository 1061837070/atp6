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

    /**
     * @msg: 多条查询
     * @param array $where = [] 查询条件，默认为空
     * @return {*}
     */
    public function getAllList(array $where = [])
    {
        $list = self::where($where)->order('sort')->select();
        return $list ? $list->toArray() : [];
    }

    /**
     * @msg: 更新单条数据
     * @param array $data 带主键的更新数据，
     * @return {*}
     */
    public function upData(array $data)
    {
        $data['updated_at'] = time();
        $data['updated_id'] = session('adminid');
        $res = self::update($data);
        return $res ? $res->toArray() : [];
    }

    /**
     * @msg: 根据条件删除数据
     * @param array $where  查询条件，查询出需要删除的数据
     * @return {*}
     */
    public function delData(array $where = [])
    {
        return self::where($where)->delete();
    }

    /**
     * @msg: 查询除指定id外，根据指定条件查询数据
     * @param array $where  查询条件
     * @param int   $id     指定的id
     * @return {*}
     */
    public function checkExit(array $where, int $id)
    {
        $res = self::where('id', '<>', $id)->where($where)->find();
		return empty($res) ? false : true;
    }

}
