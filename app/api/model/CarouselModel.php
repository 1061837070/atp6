<?php
declare(strict_types = 1);

namespace app\api\model;

use think\Model;

// 接口 轮播图
class CarouselModel extends Model
{
    protected $name = 'carousel';


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
     * @msg: 查询多条
     * @param {array} $where
     * @return {*}
     */
    public function gatAllList(array $where = [])
    {
        $list = self::where($where)->select();
        return $list ? $list->toArray() : [];
    }
}
