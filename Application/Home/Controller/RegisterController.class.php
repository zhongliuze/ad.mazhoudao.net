<?php
/**
 * FILE_NAME :  RegisterController.class.php
 * 模块:home
 * 域名:union.yanqingkong.com
 *
 * 功能:用户注册控制器
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
 * RegisterController类
 *
 * 功能1：注册页面显示
 * 功能2：用户注册校验
 * 功能3：用户信息写入数据库
 * 功能4：返回注册结果
 *
 * @author      liuzezhong <liuzezhong@hongshu.com>
 * @access      public
 * @abstract
 */
class RegisterController extends Controller {
    /**
     * 功能：注册页面显示
     */
    public function index() {
        //1.1 获取推荐ID
        $introducer_id = I('get.introducer',0,'intval');
        if($introducer_id !=0)
            $this->assign('introducer',$introducer_id);
        //1.2 显示模板
        $this->display();
    }

    /**
     * 功能：用户注册
     * @param $_POST 通过Ajax传过来的post数据
     * @return $json_encode  返回注册状态：成功、失败、失败原因
     */
    public function user_register() {

        //1.1 数据过滤校验
        $username = I('post.username','','trim,string');    //用户名
        $password = I('post.password','','trim,string');    //密码
        $public_name = I('post.public_name','','trim,string');   //公众号
        $introducer_id = I('post.introducer_id',0,'intval');   //上线ID
        $vercode = I('post.vercode','','trim,string');   //验证码

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
        if(!$public_name){
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => '公众号不能为空！',
            ));
        }
        $result = $this->check_verify($vercode);
        if($result < 1){
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => '验证码错误！',
            ));
        }
        //1.2 将用户密码进行MD5加密
        $password = md5($password .C('MD5_PRE'));;
        //1.3 数据封装
        $register_user_data = array (
            'username' => $username,
            'password' => $password,
            'public_name' => $public_name,
            'introducer_id' => $introducer_id,
            'proportion' => 0.5,  //默认分成比例为50%
        );

        //1.4 写入数据库
        try {
            //1.4.1 查询数据库中用户名是否已存在
            $res_username = D('Wmadmin')->get_one_user_by_username($username);
            if($res_username){
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '该用户名已存在！',
                ));
            }
            //1.4.2 将用户提交信息写入数据库
            $res = D('Wmadmin')->add_user($register_user_data);
            //如果存在上线用户，则上线用户的下线用户数加1
            if($introducer_id) {
                $underline_count = D('Wmadmin')->set_one_user_underline_count($introducer_id);
            }
            //1.4.3 判断注册信息，返回注册结果
            if(!$res){
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '注册失败！',
                ));
            }
            $this->get_cnumber($res);

            $this->ajaxReturn(array(
                'status' => 1,
                'message' => '注册成功！',
            ));
        } catch (Exception $e) {
            //1.4.4 如有异常发生，返回异常信息
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
    }

    /**
     * 功能：验证码检测
     * @param $code
     * @param string $id
     * @return bool
     */
    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        $verify->reset = false;   //验证成功后是否重置
        return $verify->check($code, $id);
    }

    /**
     * 功能：根据用户ID调用接口获取用户渠道号
     * @param int $user_id
     */
    function get_cnumber($user_id = 0) {
        $unionid = 2;    //联盟标识
        $postUrl = 'https://api.yanqingkong.com/unionapi/api.php';       //post地址
        $secret_key = 'dhs!@h8376726tp13y58ry31hfhsggncx,zshq9oq' ;   //传递过来的密钥
        try {
            //1.1 根据用户ID获取用户公众号信息
            $user = D('Wmadmin')->get_one_user_by_id($user_id);
            $gzh = $user['public_name'];
            //1.2 封装数据
            $postData = array(
                'unionid' => $unionid,
                'func' => 'cre_qudao',   //函数名称
                'timestamp' => time(),   //请求发起的时间戳
                'uid' => $user_id,               //用户ID
                'gzh' => $gzh,               //'公众号'
            );
            //1.3 根据键对数组进行升序排序
            ksort($postData);
            //1.4 根据方法计算公钥
            $key = '';
            foreach ($postData as $k => $item) {
                $key = $key . $k . '=' . $item . '&' ;
            }
            $key = $key . $secret_key;
            $postData['sign'] = strtolower(md5($key));
            //1.5 发送POST请求
            $res = curlData($postUrl, $postData);
            //1.6 将数据进行JSON解析
            $result = json_decode($res,true);
            //1.7 判断结果，0为正常输出,1为失败
            if($result['status'] == 0) {
                // 1.7.1 获得渠道号
                $data['c_number'] = $result['result']['qudao_id'];
                // 1.7.2 将渠道号写入数据库
                $save_cnumber = D('Wmadmin')->update_user_by_id($user_id,$data);
                // 1.7.3 写入失败抛出异常
                if(!$save_cnumber)
                    throw_exception('渠道号写入数据库失败！');
            } else if($result['status'] == 1) {
                // 1.7.4 POST请求失败抛出失败原因
                throw_exception('渠道号获取失败，失败原因：'.$result['message']);
            }
        } catch (Exception $e) {
            throw_exception($e->getMessage());
        }
    }

    /**
     * 功能：根据日期调用接口获取当日渠道销售数据
     * @param $date
     * @return mixed
     */
    function get_channel($date) {
        $unionid = 2;   //联盟标识
        $postUrl = 'https://api.yanqingkong.com/unionapi/api.php';       //post地址
        $secret_key = 'dhs!@h8376726tp13y58ry31hfhsggncx,zshq9oq' ;   //传递过来的密钥
        try {
            //1.1 封装数据
            $postData = array(
                'unionid' => $unionid,
                'func' => 'getpayloginfo',   //函数名称
                'timestamp' => time(),   //请求发起的时间戳
                'date' => $date,         //日期 如:’2017-01-01’
            );
            //1.2 根据键对数组进行升序排序
            ksort($postData);
            //1.3 根据方法计算公钥
            $key = '';
            foreach ($postData as $k => $item) {
                $key = $key . $k . '=' . $item . '&' ;
            }
            $key = $key . $secret_key;
            $postData['sign'] = strtolower(md5($key));  //公钥
            //1.4 发送POST请求
            $res = curlData($postUrl, $postData);
            //1.5 将数据进行JSON解析
            $result = json_decode($res,true);
            //1.6 判断结果，0为正常输出,1为失败
            if($result['status'] == 0) {
                //1.6.1 获取数据
                $channel_array = $result['result'];
                //1.6.2 返回数据
                foreach ($channel_array as $k => $item) {
                    $users = D('Wmadmin')->get_user_by_cnumber(intval($item['qudao_id']));
                    $channels[$k]['user_id'] = $users['user_id'];  //根据渠道号获取用户ID
                    $channels[$k]['register'] = $item['ref_users'];  // 注册用户数
                    $channels[$k]['recharge'] = $item['ref_paylogs'];   //产生订单
                    $channels[$k]['recharge_s'] = $item['ef_okpaylogs'];  //产生的成功订单数
                    $channels[$k]['commission'] = $item['ref_okmoney'];    //产生的成功订单金额
                }
                return $channels;

            } else if($result['status'] == 1) {
                // 1.6.3 POST请求失败抛出失败原因
                throw_exception('渠道号获取失败，失败原因：'.$result['message']);
            }
        } catch (Exception $e) {
            throw_exception($e->getMessage());
        }
    }
}