<?php
declare(strict_types = 1);

namespace app\api\model;

use think\Model;

// 学生api
class StudentModel extends Model
{
    protected $name = 'student';

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
        $list = self::where($where)->select();
        return $list ? $list->toArray() : [];
    }

}
