<?php
/**
 * FILE_NAME :  WmwithdrawalsModel.class.php
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
 * WmwithdrawalsModel类
 *
 * 功能1：根据用户ID按时间排序获取多条提现记录
 *
 *
 * @author      liuzezhong <liuzezhong@hongshu.com>
 * @access      public
 * @abstract
 */

class WmwithdrawalsModel extends Model {
    private $_db = '';

    /**
     * 功能：默认构造方法
     * WmwithdrawalsModel constructor.
     */
    public function __construct() {
        $this->_db = M('wms_withdrawals');
    }

    /**
     * 功能：根据用户ID按时间排序获取多条提现记录
     * @param int $user_id  用户ID
     * @return mixed 返回提现记录
     */
    public function  get_all_withdrawals_by_userid($user_id = 0) {
        if($user_id == 0)
            throw_exception('用户ID不存在！');
        $res = $this->_db->where('user_id = ' . $user_id)->order('pay_time desc')->select();
        return $res;
    }

    /**
     * 功能：获取业绩数据内容并分页显示
     * @param array $data  查询条件
     * @param $page   当前页码
     * @param int $pageSize   每页条数
     * @return mixed
     */
    public function get_all_withdrawals_limit($data = array(),$page,$pageSize = 10) {

        //1.1 将用户ID写入查询条件
        if(isset($data['user_id']))
            $selectData['user_id'] = $data['user_id'];
        if(isset($data['status']) && $data['status'] != -2) {
            if($data['status'] == 2)
                $selectData['pay_status'] = 0;
            else
                $selectData['pay_status'] = $data['status'];
        }

        if(isset($data['user_array'])) {
            $user_array = $data['user_array'];
            $selectData['user_id'] = array('IN',$user_array);
        }
        //1.2 如果存在REQUEST提交的数据，则写入查询条件
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['pay_time'] = array('BETWEEN',$data['sql_date']);   //查询表达式
        }
        //1.3 设置起始位置
        $offset = ($page - 1) * $pageSize;
        //1.4 查询数据按时间、ID号降序排列
        $list = $this->_db->where($selectData)->order('pay_time desc,user_id desc')->limit($offset,$pageSize)->select();
        //1.5 返回查询结果
        return $list;
    }

    /**
     * 功能：获取总记录数
     * @return mixed
     */
    public function get_count_withdrawals($data = array()){
        //1.1 将用户ID写入查询条件
        if(isset($data['user_id']))
            $selectData['user_id'] = $data['user_id'];
        if(isset($data['status']) && $data['status'] != -2) {
            if($data['status'] == 2)
                $selectData['pay_status'] = 0;
            else
                $selectData['pay_status'] = $data['status'];
        }

        if(isset($data['user_array'])) {
            $user_array = $data['user_array'];
            $selectData['user_id'] = array('IN',$user_array);
        }

        //1.2 如果存在REQUEST提交的数据，则写入查询条件
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['pay_time'] = array('BETWEEN',$data['sql_date']);  //查询表达式
        }
        //1.3 返回查询结果
        return $this->_db->where($selectData)->count();
    }

    /**
     *判断时间段是否有周末。有两个已知时间戳。判断该时间戳内是否包含周六周天。
     * @author winter
     * @version 2015年10月10日17:36:27
     * @param begin 开始时间
     * @param last 结束时间
     */
    public function isWeek($begin,$last){
        $span = intval($last-$begin);
        if($span >= 604800){
            return 1;
        }else if($span > 0){
            $lWeek = date('w',$last);
            $bWeek = date('w',$begin);
            if($lWeek == 6 || $lWeek == 0 || $bWeek == 6 || $bWeek == 0){
                return 1;
            }else{
                if($bWeek > $lWeek){
                    return 1;
                }else{
                    return 0;
                }
            }
        }
    }

    /**
     * 功能：添加一条提现记录
     * @param array $data
     * @return mixed
     */
    public function add_one_withdrawals($data = array()){
        if(!$data || !isset($data)){
            throw_exception('数据不存在！');
        }
        return $this->_db->add($data);
    }

    /**
     * 功能：将逗号、空格、回车分隔的字符串转换为数组的函数
     * @param $strs
     * @return array
     */
    public function strsToArray($strs = array()) {
        $result = array();
        $arrays = array();
        $strs = str_replace('，', ',', $strs);
        $strs = str_replace("n", ',', $strs);
        $strs = str_replace("rn", ',', $strs);
        $strs = str_replace(' ', ',', $strs);
        $arrays = explode(',', $strs);
        foreach ($arrays as $key => $value) {
            if ('' != ($value = trim($value))) {
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * 功能：更新提现状态
     * @param int $type
     * @param array $data
     * @return bool
     */
    public function update_withdrawals_status_in($type = 0,$data = array()) {
        if(!is_array($data)) {
            throw_exception('参数不合法!');
        }
        if($type == 1)
            $set_status['pay_status'] = 1;   //提现成功
        else if($type == -1)
            $set_status['pay_status'] = -1;   //提现失败

        $pay_id = array('serial_number' => array('in',implode(',',$data)),);

        return $this->_db->where($pay_id)->save($set_status);
    }

    /**
     * 功能：根据提现记录号获取一条提现记录
     * @param int $serial_number
     */
    public function get_one_withdrawal_by_serialnumber($serial_number = 0) {
        return $this->_db->where('serial_number = ' . $serial_number)->find();
    }

    public function get_duigong_tixian($data = array()) {
        return $this->_db->where($data)->select();
    }

    public function update_tixian_by_id($serial_number=0 ,$data=array()){
        return $this->_db->where('serial_number = ' . $serial_number)->save($data);
    }
}