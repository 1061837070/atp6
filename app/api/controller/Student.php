<?php
declare(strict_types = 1);

namespace app\api\controller;

use app\api\BaseController;
use app\api\model\StudentModel;

header('Access-Control-Allow-Origin:*'); 
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE'); 

/**
 * 后台首页
 */
class Student extends BaseController
{
    /**
     * @msg: 学生分页
     * @param {*}
     * @return {*}
     */
    public function stuList()
    {
        $studentModel = new StudentModel();
        $list = $studentModel->getAllList();

        return json($list);
    }
}
