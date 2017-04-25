<?php
/**
 * Created by PhpStorm.
 * User: yuban
 * Date: 2017/2/28
 * Time: 14:03
 */

namespace Home\Controller;


use Think\Controller;

class CommonController extends Controller {
    /**
     * 构造方法
     * CommonController constructor.
     */
    public function __construct() {
        header("Content-type: text/html; charset=utf-8");
        parent::__construct();
        $this->_init();  //调用初始化方法
    }

    /**
     * 初始化
     * @return
     */
    private function _init() {
        //1.1 如果已经登录
        $isLogin = $this->isLogin();
        if($isLogin) {
            $user = D('Wmadmin')->get_one_user_by_id($_SESSION['adminUser']['user_id']);
            if(!$user) {
                //1.2 跳转到登录页面
                redirect(U('home/login/loginout'));
            }
        }
        if(!$isLogin) {
        //1.2 跳转到登录页面
        	redirect(U('home/login/index'));
        }
    }

    /**
     * 获取登录用户信息
     * @return array
     */
    public function getLoginUser() {
        return session("adminUser");
    }

    /**
     * 判定是否登录
     * @return boolean
     */
    public function isLogin() {
        $user = $this->getLoginUser();
        if($user && is_array($user)) {
            return true;
        }
        return false;
    }
}