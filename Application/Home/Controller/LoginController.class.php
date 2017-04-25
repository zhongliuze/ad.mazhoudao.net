<?php
/**
 * FILE_NAME :  LoginController.class.php
 * 模块:home
 * 域名:union.yanqingkong.com
 *
 * 功能:用户登陆控制器
 *
 *
 * @copyright Copyright (c) 2017 – www.hongshu.com
 * @author liuzezhong@hongshu.com
 * @version 1.0 2017/2/28 14:06
 */

namespace Home\Controller;


use Think\Controller;
use Think\Exception;

/**
 * LoginController类
 *
 * 功能1：登陆页面显示
 * 功能2：用户登陆校验
 * 功能3：登录信息加入session
 * 功能4：返回登录结果
 *
 * @author      liuzezhong <liuzezhong@hongshu.com>
 * @access      public
 * @abstract
 */
class LoginController extends Controller {

    public function index() {
        //1.1 如果已经登录则跳转至后台主页
        if($_SESSION['adminUser']) {
            redirect(U('home/usermenu/index'));
        }
        //1.2 显示模板
        $this->display();
    }

    /**
     * 功能：用户登陆校验
     * @param $_POST 通过Ajax传过来的post数据
     * @return $json_encode  返回校验状态：成功、失败、失败原因
     */

    public function verify_login() {

        //1.1 数据过滤校验
        $username = I('post.username','','trim,string');
        $password = I('post.password','','trim,string');
        $vercode = I('post.vercode','','trim,string');
        if(!$username){
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => '用户名不能为空！',
            ));
        }
        if(!$password){
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => '密码不能为空！',
            ));
        }
        if(!$vercode){
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => '验证码不能为空！',
            ));
        }

        //1.2 检验验证码
        $result = $this->check_verify($vercode);
        if($result < 1){
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => '验证码错误！',
            ));
        }

        //1.3 数据库操作
        try {
            //1.3.1 根据用户名获取一条用户记录
            $res = D('Wmadmin')->get_one_user_by_username($username);
            //1.3.2 根据记录进行判断用户是否存在
            if(!$res){
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '用户不存在！',
                ));
            }
            //1.3.3 比对用户密码

            if(md5($password .C('MD5_PRE')) != $res['password']){
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '密码错误！',
                ));
            }
            //1.3.4 比对用户状态
            if($res['user_status'] == 0){
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '该账户已被冻结！',
                ));
            }
            //1.3.4 将登陆信息写入session
            session('adminUser',$res);
            //1.3.5 返回登录结果
            $this->ajaxReturn(array(
                'status' => 1,
                'message' => '登陆成功！',
            ));

        } catch (Exception $e) {
            //1.3.6 如有异常发生，返回异常信息
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
    }

    /**
     * 功能：退出登陆
     */
    public function loginout() {
        //1.1 session置空
        session('adminUser',null);
        //1.2 跳转至首页
        redirect(U('/home/index'));
    }

    /**
     * 功能：验证码类
     */
    public function  verify() {
        $config =    array(
            'length'      =>    4,     // 验证码位数
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    /**
     * 功能：验证码检测
     * @param $code
     * @param string $id
     * @return bool
     */
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        $verify->reset = false;       //验证成功后是否重置
        return $verify->check($code, $id);
    }

}