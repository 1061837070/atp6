<?php
declare(strict_types = 1);

namespace app\back\model;

use think\Model;

/**
 * 后台 功能规则 model
 */
class RuleModel extends Model
{
    protected $name = 'rule';

    /**
     * @msg: 查询单条
     * @param array $where = [] 查询条件，默认为空
     * @return array
     */
    public function getInfo(array $where = [])
    {
        $res = self::where($where)->find();
        return $res ? $res->toArray() : [];
    }

    /**
     * @msg: 查询多条
     * @param array $where = [] 查询条件，默认为空
     * @return array
     */
    public function getAllList(array $where = [])
    {
        $list = self::where($where)->order('sort')->select();
        return $list ? $list->toArray() : [];
    }

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
     * @msg: 添加多条
     * @param array $data 添加的数据，包含多个数据的二维数组
     * @return {*}
     */
    public function addDatas(array $data)
    {
        $res = self::saveAll($data);
        return $res ? $res->toArray() : [];
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
     * @msg: 更新多条数据
     * @param array $data 更新的多条数据的二维数组，每个数组包含主键
     * @return {*}
     */
    public function upDatas(array $data)
    {
        $res = self::saveAll($data);
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
