<?php
declare(strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\CarouselModel;
use app\back\validate\Vcarouse;
use think\exception\ValidateException;
use think\facade\Db;

// 轮播图
class CarouselPic extends BaseController
{
    protected $noNeedLogin = [];

    /**
     * @msg: 管理员列表
     * @param {*}
     * @return {*}
     */
    public function index()
    {
        if (request()->isAjax()) {
            $carModel = new CarouselModel;
            $list = $carModel->gatAllList();
            return json(['code' => 200, 'data' => $list]);
        }
        $hbtns = $this->show_btn(['add']);
        $mbtns = $this->show_btn(['edit', 'del']);
        $isOperate = empty($mbtns) ? 0 : 1;
    
        return view('index', ['hbtns'=>$hbtns,'mbtns'=>$mbtns,'isOperate'=>$isOperate]);
    }

    /**
     * @msg: 添加管理员
     * @param {*}
     * @return {*}
     */
    public function add()
    {
        $carModel = new CarouselModel;
        if (request()->isAjax()) {
            $params = request()->param();
            $params = trim_arr($params);
            
            try {
                validate(Vcarouse::class)->check([
                    'url'  => $params['url'],
                    'car_pic' => $params['car_pic']
                ]);
            } catch (ValidateException $e) {
                return json(['code' => 400, 'msg' => $e->getError()]);
            }
            $params['created_at'] = time();
            $res = $carModel->addData($params);
            if ($res) {
                return json(['code' => 200, 'msg' => '添加成功']);
            } else {
                return json(['code' => 400, 'msg' => '添加失败']);
            }
        }

        return view('add');
    }

    /**
     * @msg: 编辑管理员
     * @param {*}
     * @return {*}
     */
    public function edit()
    {
        $adminModel = new AdminModel;
        if (request()->isAjax()) {
            $params = request()->param();
            $params = trim_arr($params);
            $adminId = intval($params['id']);

            $adminInfo = $adminModel->getInfo(['id' => $adminId]);
            if (empty($adminInfo)) {
                return json(['code' => 400, 'msg' => '管理员不存在']);
            }
            if ($adminInfo['nick_name'] == 'admin') {
                return json(['code' => 400, 'msg' => '信息错误']);
            }
            if (!preg_match('/^1\d{10}$/', $params['phone'])) {
                return json(['code' => 400, 'msg' => '手机号格式错误']);
            }

            try {
                validate(Vadmin::class)->check([
                    'nick_name'  => $params['nick_name'],
                    'phone' => $params['phone'],
                    'rose_id' => $adminInfo['rose_id'],
                    'email' => $params['email']
                ]);
            } catch (ValidateException $e) {
                return json(['code' => 400, 'msg' => $e->getError()]);
            }

            $eixt1 = $adminModel->checkExit(['nick_name' => $params['nick_name']], $adminId);
            if ($eixt1) {
                return json(['code' => 400, 'msg' => '昵称已存在']);
            }
            $eixt2 = $adminModel->checkExit(['phone' => $params['phone']], $adminId);
            if ($eixt2) {
                return json(['code' => 400, 'msg' => '手机号已存在']);
            }

            // 修改数据
            $res = $adminModel->upData($params);
            if ($res) {
                return json(['code' => 200, 'msg' => '修改成功']);
            } else {
                return json(['code' => 400, 'msg' => '修改失败']);
            }
        }

        $adminId = request()->param('id');
        $adminInfo = $adminModel->getInfo(['id' => $adminId]);
        if (empty($adminInfo)) {
            $icon = '/iconstr/layui-icon-404';
            $msgstr = '/msgstr/编辑的信息不存在';
            $url = str_replace('/', '*', '/back/index/index');
            $urlstr = '/urlstr/'.$url;

            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        if ($adminInfo['nick_name'] == 'admin') {
            $icon = '/iconstr/layui-icon-close-fill';
            $msgstr = '/msgstr/编辑的信息错误';
            $url = str_replace('/', '*', '/back/index/index');
            $urlstr = '/urlstr/'.$url;

            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        return view('edit', ['adminInfo'=>$adminInfo]);
    }

    /**
     * @msg: 删除账号
     * @param {*}
     * @return {*}
     */
    public function del()
    {
        if (request()->isAjax()) {
            $params = request()->param();
            if (!isset($params['id'])) {
                return json(['code' => 400, 'msg' => '数据错误']);
            }

            $adminModel = new AdminModel;
            $adminId = intval($params['id']);
            $adminInfo = $adminModel->getInfo(['id' => $adminId]);
            if (empty($adminInfo)) {
                return json(['code' => 400, 'msg' => '数据不存在']);
            }
            if ($adminInfo['nick_name'] == 'admin') {
                return json(['code' => 400, 'msg' => '非法操作']);
            }

            $res = $adminModel->delData(['id' => $adminId]);
            if ($res) {
                return json(['code' => 200, 'msg' => '删除成功']);
            } else {
                return json(['code' => 400, 'msg' => '删除失败']);
            }
        }
    }

    /**
     * @msg: 上传管理员头像接口
     * @param {*}
     * @return {*}
     */
    public function upload()
    {
        $file = request()->file('car_pic');
        try {
            validate(['file' => ['fileSize:1024000', 'fileExt:jpg,jpeg,png,gif']])->check(['file' => $file]);
        } catch (\think\exception\ValidateException $e) {
            return json(['code' => 400, 'msg' => $e->getMessage()]);
        }
        $savename = \think\facade\Filesystem::disk('public')->putFile('car_pic', $file);
        return json(['code' => 200, 'path_info' => '/storage/'.str_replace('\\', '/', $savename)]);
    }
}
