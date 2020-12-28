<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\AdminModel;
use app\back\model\RoseModel;
use app\back\validate\Vadmin;
use think\exception\ValidateException;
use think\facade\Db;

/**
 * Class Admin 后台 管理员账号
 * @package app\back\controller
 */
class Admin extends BaseController
{
    protected $noNeedLogin = ['addSuperAdmin'];

    /**
     * @msg: 管理员列表
     * @param {*}
     * @return {*}
     */
    public function index()
    {
        if (request()->isAjax()) {
            $list = Db::table('tp6_admin')
                ->alias('a')
                ->where('a.id','<>',1)
                ->leftJoin('tp6_admin ca','a.created_id = ca.id')
                ->leftJoin('tp6_admin ua','a.updated_id = ua.id')
                ->leftJoin('tp6_rose r','a.rose_id = r.id')
                ->field('a.*')
                ->field('ca.nick_name as cname')
                ->field('ua.nick_name as uname')
                ->field('r.name as rose_name')
                ->order('a.id')
                ->select()
                ->toArray();
            return json(['code' => 200, 'data' => $list]);
        }
        $hbtns = $this->show_btn(['add']);
        $mbtns = $this->show_btn(['edit', 'setRose', 'resetPwd', 'stop', 'start', 'del']);
        $isOperate = empty($mbtns) ? 0 : 1;
    
        return view('index',['hbtns'=>$hbtns,'mbtns'=>$mbtns,'isOperate'=>$isOperate]);
    }

    /**
     * @msg: 添加管理员
     * @param {*}
     * @return {*}
     */
    public function add()
    {
        $roseModel = new RoseModel;
        if (request()->isAjax()) {
            $params = request()->param();
            $params = trim_arr($params);
            
            try {
                validate(Vadmin::class)->check([
                    'nick_name'  => $params['nick_name'],
                    'phone' => $params['phone'],
                    'rose_id' => $params['rose_id'],
                    'email' => $params['email'],
                ]);
            } catch (ValidateException $e) {
                return json(['code' => 400, 'msg' => $e->getError()]);
            }

            if (!preg_match('/^1\d{10}$/', $params['phone'])) {
                return json(['code' => 400, 'msg' => '手机号格式错误']);
            }
            
            $eixt1 = $roseModel->getInfo(['id' => $params['rose_id']]);
            if (empty($eixt1)) {
                return json(['code' => 400, 'msg' => '角色选择错误']);
            }
            $adminModel = new AdminModel;
            $eixt2 = $adminModel->getInfo(['nick_name' => $params['nick_name']]);
            if (!empty($eixt2)) {
                return json(['code' => 400, 'msg' => '昵称已存在']);
            }
            $eixt3 = $adminModel->getInfo(['phone' => $params['phone']]);
            if (!empty($eixt3)) {
                return json(['code' => 400, 'msg' => '手机号已存在']);
            }

            // 添加数据
            $params['created_at'] = time();
            $params['created_id'] = session('adminid');
            $pwd = '123456';
            $params['pwd_salt'] = bin2hex(random_bytes(3));
            $params['password'] = set_enc_word($params['pwd_salt'], $pwd, 'back');

            $res = $adminModel->addData($params);
            if ($res) {
                return json(['code' => 200, 'msg' => '添加成功']);
            } else {
                return json(['code' => 400, 'msg' => '添加失败']);
            }
        }

        // 管理员角色
        $roseList = $roseModel->getAllList([['name', '<>', '超级管理员']]);
        return view('add', ['roseList'=>$roseList]);
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
            $url = str_replace('/','*','/back/index/index');
            $urlstr = '/urlstr/'.$url;

            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        if ($adminInfo['nick_name'] == 'admin') {
            $icon = '/iconstr/layui-icon-close-fill';
            $msgstr = '/msgstr/编辑的信息错误';
            $url = str_replace('/','*','/back/index/index');
            $urlstr = '/urlstr/'.$url;

            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        return view('edit', ['adminInfo'=>$adminInfo]);
    }

    /**
     * @msg: 分配角色
     * @param {*}
     * @return {*}
     */
    public function setRose()
    {
        $adminModel = new AdminModel;
        $roseModel = new RoseModel;
        if (request()->isAjax()) {
            $params = request()->param();
            $adminId = intval($params['id']);
            $roseId = intval($params['rose_id']);

            $adminInfo = $adminModel->getInfo(['id' => $adminId]);
            if (empty($adminInfo)) {
                return json(['code' => 400, 'msg' => '管理员不存在']);
            }
            if ($adminInfo['nick_name'] == 'admin') {
                return json(['code' => 400, 'msg' => '信息错误']);
            }

            // 修改数据
            $res = $adminModel->upData($params);
            if ($res) {
                return json(['code' => 200, 'msg' => '操作成功']);
            } else {
                return json(['code' => 400, 'msg' => '操作失败']);
            }
        }

        $adminId = request()->param('id');
        $adminInfo = $adminModel->getInfo(['id' => $adminId]);
        if (empty($adminInfo)) {
            $icon = '/iconstr/layui-icon-404';
            $msgstr = '/msgstr/信息不存在';
            $url = str_replace('/','*','/back/index/index');
            $urlstr = '/urlstr/'.$url;

            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        if ($adminInfo['nick_name'] == 'admin') {
            $icon = '/iconstr/layui-icon-close-fill';
            $msgstr = '/msgstr/信息错误';
            $url = str_replace('/','*','/back/index/index');
            $urlstr = '/urlstr/'.$url;

            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        $roseList = $roseModel->getAllList([['name', '<>', '超级管理员']]);
        unset($adminInfo['password']);
        unset($adminInfo['pwd_salt']);
        return view('setRose', ['roseList'=>$roseList, 'adminInfo'=>$adminInfo]);
    }

    /**
     * @msg: 重置密码
     * @param {*}
     * @return {*}
     */
    public function resetPwd()
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

            // 重置密码
            $pwd = '123456';
            $data['pwd_salt'] = bin2hex(random_bytes(3));
            $data['password'] = set_enc_word($data['pwd_salt'], $pwd, 'back');
            $data['id'] = $adminId;
            $res = $adminModel->upData($data);
            if ($res) {
                return json(['code' => 200, 'msg' => '重置成功']);
            } else {
                return json(['code' => 400, 'msg' => '重置失败']);
            }
        }
    }

    /**
     * @msg: 禁用账号
     * @param {*}
     * @return {*}
     */
    public function stop()
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
            if ($adminInfo['status'] == 2) {
                return json(['code' => 400, 'msg' => '账号已禁用，请勿再次操作']);
            }

            $data['id'] = $adminId;
            $data['status'] = 2;
            $res = $adminModel->upData($data);
            if ($res) {
                return json(['code' => 200, 'msg' => '操作成功']);
            } else {
                return json(['code' => 400, 'msg' => '操作失败']);
            }
        }
    }

    /**
     * @msg: 启用账号
     * @param {*}
     * @return {*}
     */
    public function start()
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
            if ($adminInfo['status'] == 1) {
                return json(['code' => 400, 'msg' => '账号已启用，请勿再次操作']);
            }

            $data['id'] = $adminId;
            $data['status'] = 1;
            $res = $adminModel->upData($data);
            if ($res) {
                return json(['code' => 200, 'msg' => '操作成功']);
            } else {
                return json(['code' => 400, 'msg' => '操作失败']);
            }
        }
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
        $file = request()->file('header_pic');
        try {
            validate(['file' => ['fileSize:1024000', 'fileExt:jpg,jpeg,png,gif']])->check(['file' => $file]);
        } catch (\think\exception\ValidateException $e) {
            return json(['code' => 400, 'msg' => $e->getMessage()]);
        }
        $savename = \think\facade\Filesystem::disk('public')->putFile( 'admin_header_pic', $file);
        return json(['code' => 200, 'path_info' => '/storage/'.str_replace('\\', '/', $savename)]);
    }

    /**
     * @msg: 添加超级管理员 初始化时直接浏览器地址操作 只能添加一次
     * @param {*}
     * @return {*}
     */
    public function addSuperAdmin()
    {
        $params['nick_name'] = 'admin';
        $params['true_name'] = 'admin';

        $adminModel = new AdminModel;

        $exit1 = $adminModel->getInfo(['nick_name' => $params['nick_name']]);
        if ($exit1) {
            return '超级管理员已存在';
        }

        $params['phone'] = '';
        $pwd = '123456';
        $params['pwd_salt'] = bin2hex(random_bytes(3));
        $params['password'] = set_enc_word($params['pwd_salt'], $pwd, 'back');
        $params['rose_id'] = 1;
        $params['created_at'] = time();
        $params['created_id'] = 1;

        $res = $adminModel->addData($params);
        if ($res) {
            return '超级管理员添加成功';
        } else {
            return '超级管理员添加失败';
        }
    }

}
