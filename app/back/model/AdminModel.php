<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: sueRimn
 * @Date: 2020-12-03 18:10:04
 * @LastEditors: sueRimn
 * @LastEditTime: 2020-12-28 18:43:02
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
     * @msg: 查询除指定id外，根据指定条件查询数据是否存在
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
