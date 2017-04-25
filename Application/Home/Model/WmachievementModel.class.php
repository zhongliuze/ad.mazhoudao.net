<?php
/**
 * FILE_NAME :  WmachievementModel.class.php
 * 模块:home
 * 域名:union.yanqingkong.com
 *
 * 功能:用户业绩表操作模型层
 *
 *
 * @copyright Copyright (c) 2017 – www.hongshu.com
 * @author liuzezhong@hongshu.com
 * @version 1.0 2017/3/3 10:14
 */

namespace Home\Model;
use Think\Model;

/**
 * WmachievementModel类
 *
 * 功能1：根据用户ID按日期排序获取多条业绩数据
 *
 *
 * @author      liuzezhong <liuzezhong@hongshu.com>
 * @access      public
 * @abstract
 */
class WmachievementModel extends Model {
    private $_db = '';
    /**
     *Model constructor.
     *Model的默认构造方法
     */
    public function __construct() {
        $this->_db = M('wms_achievement');
    }

    /**
     * 功能：根据用户ID按日期排序获取多条业绩数据
     * @param int $user_id 用户ID
     * @return mixed 返回多个包含该ID的业绩数据
     */
    public function get_all_achievement_by_userid($user_id = 0) {
        if($user_id == 0)
            throw_exception('用户ID为空！');
        $res = $this->_db->where('user_id = ' . $user_id)->order('re_time desc')->select();
        return $res;
    }

    /**
     * 功能：获取业绩数据内容并分页显示
     * @param array $data  查询条件
     * @param $page   当前页码
     * @param int $pageSize   每页条数
     * @return mixed
     */
    public function get_all_achievement_limit($data = array(),$page,$pageSize = 10) {
        //1.1 校验数据
        /*if(!isset($data['user_id']) || !$data['user_id'])
            throw_exception('用户ID不存在！');*/
        //1.2 将用户ID写入查询条件
        if($data['user_id'])
            $selectData['user_id'] = $data['user_id'];
        //1.3 如果存在REQUEST提交的数据，则写入查询条件
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['re_time'] = array('BETWEEN',$data['sql_date']);   //查询表达式
        }

        if($data['ach_type'])
            $selectData['ach_type'] = $data['ach_type'];
        //1.4 设置起始位置
        $offset = ($page - 1) * $pageSize;
        //1.5 查询数据按时间、ID号降序排列
        $list = $this->_db->where($selectData)->order('re_time desc')->limit($offset,$pageSize)->select();
        //1.6 返回查询结果
        return $list;
    }

    /**
     * 功能：获取总记录数
     * @return mixed
     */
    public function get_count_achievement($data = array()){
        //1.1 数据校验
        /*if(!isset($data['user_id']) || !$data['user_id'])
            throw_exception('用户ID不存在！');*/
        //1.2 将用户ID写入查询条件
        if($data['user_id'])
            $selectData['user_id'] = $data['user_id'];
        //1.3 如果存在REQUEST提交的数据，则写入查询条件
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['re_time'] = array('BETWEEN',$data['sql_date']);  //查询表达式
        }
        if($data['ach_type'])
            $selectData['ach_type'] = $data['ach_type'];
        //1.4 返回查询结果
        return $this->_db->where($selectData)->count();
    }

    /**
     * 功能：根据业绩ID号修改ID号对应的记录值
     * @param int $ach_id
     * @param array $data
     * @return bool
     */
    public function update_one_achievement_by_id($ach_id = 0,$data = array()){
        if($ach_id == 0 || !$ach_id)
            throw_exception('该记录ID号不存在！');
        if(!$data || !isset($data))
            throw_exception('数据不存在！');
        return $this->_db->where('ach_id = ' . $ach_id)->save($data);
    }

    /**
     * 功能：获取所有的用户业绩
     * @return mixed
     */
    public function get_all_achievement(){
        return $this->_db->select();
    }

    /**
     * 功能：添加一条用户业绩
     * @param array $data
     * @return mixed
     */
    public function add_one_achievement($data = array()) {
        if(!is_array($data) || !$data)
            throw_exception('数据为空！');
        return $this->_db->add($data);
    }
}