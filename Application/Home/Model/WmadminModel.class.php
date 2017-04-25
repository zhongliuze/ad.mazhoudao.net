<?php

/**
 * FILE_NAME :  WmadminModel.class.php
 * 模块:home
 * 域名:union.yanqingkong.com
 *
 * 功能:用户信息表操作模型层
 *
 *
 * @copyright Copyright (c) 2017 – www.hongshu.com
 * @author liuzezhong@hongshu.com
 * @version 1.0 2017/2/28 14:06
 */
namespace Home\Model;
use Think\Model;

/**
 * WmadminModel类
 *
 * 功能1：根据用户名查找一条用户记录
 * 功能2：新增一条用户记录
 *
 * @author      liuzezhong <liuzezhong@hongshu.com>
 * @access      public
 * @abstract
 */
class WmadminModel extends Model {
    private $_db = '';

    /**
     * WmadminModel constructor.
     * WmadminModel的默认构造方法
     */
    public function __construct() {
        $this->_db = M('wms_user');
    }

    /**
     * 功能：根据用户名查找用户记录
     * @param string $username  用户昵称
     * @return mixed  返回包含该用户昵称的一条记录
     */
    public function get_one_user_by_username($username = '') {
        if(!$username || !isset($username))
            throw_exception('用户名不存在！');

        $data = array (
            'user_status' => array('neq',-1),
            'username' => $username,
        );

        $res = $this->_db->where($data)->find();
        return $res;
    }

    /**
     * 功能：新增一条用户数据
     * @param array $data 包含用户数据的数组
     * @return mixed  返回一个状态
     */
    public function add_user($data = array()) {
        if(!$data || !is_array($data)) {
            throw_exception('新增用户数据不存在！');
        }
        $data['register_time'] = time();
        $res = $this->_db->add($data);
        return $res;
    }

    /**
     * 功能：根据用户ID查询用户信息
     * @param int $user_id 用户ID
     * @return mixed  返回指定ID对应的一条记录
     */
    public function get_one_user_by_id($user_id = 0) {
        return $this->_db->where('user_id = '. $user_id)->find();
    }

    /**
     * 功能：根据用户渠道号获取用户信息
     * @param int $c_number
     * @return mixed
     */
    public function get_one_user_by_cnumber($c_number = 0){
        if($c_number == 0)
            throw_exception('渠道号不存在！');
        return $this->_db->where('c_number = ' .  $c_number)->find();
    }

    /**
     * 功能：根据用户ID修改用户信息
     * @param int $user_id
     * @param array $data
     * @return bool
     */
    public function update_user_by_id($user_id = 0 ,$data = array()) {
        if($user_id == 0)
            throw_exception('用户ID不存在！');
        if(!$data || !is_array($data))
            throw_exception('用户数据不存在！');
        return $this->_db->where('user_id = ' . $user_id)->save($data);
    }

    /**
     * 功能：根据用户ID查询用户下线
     * @param int $user_id
     * @return mixed
     */
    public function get_all_user_underline_by_id($user_id = 0) {
        if($user_id == 0)
            throw_exception('用户ID不存在');
        return $this->_db->where('introducer_id = ' . $user_id)->order('register_time desc')->select();

    }

    /**
     * 功能：获取下线信息并分页显示
     * @param array $data  查询条件
     * @param $page   当前页码
     * @param int $pageSize   每页条数
     * @return mixed
     */
    public function get_all_underline_limit($data = array(),$page,$pageSize = 10) {
        //1.1 校验数据
        if(!isset($data['user_id']) || !$data['user_id'])
            throw_exception('用户ID不存在！');
        //1.2 将用户ID写入查询条件
        $selectData['introducer_id'] = $data['user_id'];
        //1.3 如果存在REQUEST提交的数据，则写入查询条件
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['register_time'] = array('BETWEEN',$data['sql_date']);   //查询表达式
        }
        if(isset($data['username']))
            $selectData['username'] = $data['username'];

                //1.4 设置起始位置
        $offset = ($page - 1) * $pageSize;
        //1.5 查询数据按时间、ID号降序排列
        $list = $this->_db->where($selectData)->order('register_time desc,user_id desc')->limit($offset,$pageSize)->select();
        //1.6 返回查询结果
        return $list;
    }

    /**
     * 功能：获取总记录数
     * @return mixed
     */
    public function get_count_underline($data = array()){
        //1.1 数据校验
        if(!isset($data['user_id']) || !$data['user_id'])
            throw_exception('用户ID不存在！');
        //1.2 将用户ID写入查询条件
        $selectData['introducer_id'] = $data['user_id'];
        //1.3 如果存在REQUEST提交的数据，则写入查询条件
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['register_time'] = array('BETWEEN',$data['sql_date']);  //查询表达式
        }
        if(isset($data['username']))
            $selectData['username'] = $data['username'];
        //1.4 返回查询结果
        return $this->_db->where($selectData)->count();
    }

    /**
     * 功能：获取下线信息并分页显示
     * @param array $data  查询条件
     * @param $page   当前页码
     * @param int $pageSize   每页条数
     * @return mixed
     */
    public function get_all_useradmin_limit($data = array(),$page,$pageSize = 10) {
        $selectData = array();
        if(isset($data['username']))
            $selectData['username'] = $data['username'];
        if(isset($data['public_name']))
            $selectData['public_name'] = $data['public_name'];
        if(isset($data['introducer_id']))
            $selectData['introducer_id'] = $data['introducer_id'];

        //1.4 设置起始位置
        $offset = ($page - 1) * $pageSize;
        //1.5 查询数据按时间、ID号降序排列
        $list = $this->_db->where($selectData)->order('register_time desc,user_id desc')->limit($offset,$pageSize)->select();
        //1.6 返回查询结果
        return $list;
    }

    /**
     * 功能：获取总记录数
     * @return mixed
     */
    public function get_count_useradmin($data = array()){
        $selectData = array();
        if(isset($data['username']))
            $selectData['username'] = $data['username'];
        if(isset($data['public_name']))
            $selectData['public_name'] = $data['public_name'];
        if(isset($data['introducer_id']))
            $selectData['introducer_id'] = $data['introducer_id'];
        //1.4 返回查询结果
        return $this->_db->where($selectData)->count();
    }

    public function get_all_useradmin() {
        return $this->_db->select();
    }

    /**
     * 功能：根据用户ID将用户的下线个数增1
     * @param $user_id
     * @return bool
     */
    public function set_one_user_underline_count($user_id) {
        return $this->_db->where('user_id = ' . $user_id)->setInc('underline_count');
    }


}