<?php
declare(strict_types = 1);

namespace app\api\controller;

use app\api\BaseController;
use app\api\model\CarouselModel;

// 轮播图接口
class Carousel extends BaseController
{
    
    public function getList()
    {
        $carouseModel = new CarouselModel();
        $list = $carouseModel->gatAllList();

        return json(['code' => 200, 'data' => $list]);
    }
}
