<?php
declare(strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;

class Test extends BaseController
{
    public function index()
    {
        $arr = [
            'id' => 1,
            'name' => '张三',
            'phone' => '18011112222',
        ];

        return json($arr);
    }

    public function test()
    {
        return "789";
    }

    public function test2()
    {
        return "100";
    }

    public function test3($name)
    {
        return $name;
    }

    public function test4()
    {
        $bytes = random_bytes(2);
        $int = random_int(100000, 999999);
        $str = cc('aa', 'bb');

        print_r(bin2hex($bytes));
        echo "<br>";
        print_r($int);
        echo "<br>";
        print_r($str);

        return view('test4', ['name' => '张三', 'phone' => '13011112222']);
    }

    public function test5()
    {
        return view();
    }

    // 助手函数重定向
    public function test6()
    {
        // return redirect('http://www.baidu.com'); //直接重定向
        // return redirect('/back/test3/哈哈'); //重定向传参
    }
}
