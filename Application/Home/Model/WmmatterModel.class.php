<?php
/**
 * FILE_NAME :  WmmatterModel.class.php
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
class WmmatterModel extends Model {
    private $_db = '';

    /**
     * 功能：默认构造方法
     * WmwithdrawalsModel constructor.
     */
    public function __construct() {
        $this->_db = M('wms_matter');
    }

    /**
     * 功能：获取全部素材内容根据上传时间降序排列
     * @return mixed
     */
    public function get_all_matter() {
        return $this->_db->order('upload_time desc')->select();
    }

    /**
     * 功能：获取素材并分页显示
     * @param array $data  查询条件
     * @param $page   当前页码
     * @param int $pageSize   每页条数
     * @return mixed
     */
    public function get_all_matter_limit($data = array(),$page,$pageSize = 10) {

        //1.3 如果存在REQUEST提交的数据，则写入查询条件
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['upload_time'] = array('BETWEEN',$data['sql_date']);   //查询表达式
        }
        if(isset($data['bookname']))
            $selectData['catename'] = array('like','%'.$data['bookname'].'%');
        if(isset($data['type_id']))
            $selectData['type_id'] = $data['type_id'];
        if(isset($data['bookid']))
            $selectData['bid'] = $data['bookid'];

        //1.4 设置起始位置
        $offset = ($page - 1) * $pageSize;
        //1.5 查询数据按时间、ID号降序排列
        $list = $this->_db->where($selectData)->order('upload_time desc')->limit($offset,$pageSize)->select();
        //1.6 返回查询结果
        return $list;
    }

    /**
     * 功能：获取总记录数
     * @return mixed
     */
    public function get_count_matter($data = array()){
        //1.1 数据校验

        //1.3 如果存在REQUEST提交的数据，则写入查询条件
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['upload_time'] = array('BETWEEN',$data['sql_date']);  //查询表达式
        }
        if(isset($data['bookname']))
            $selectData['catename'] = array('like','%'.$data['bookname'].'%');
        if(isset($data['type_id']))
            $selectData['type_id'] = $data['type_id'];
        if(isset($data['bookid']))
            $selectData['bid'] = $data['bookid'];
        //1.4 返回查询结果
        return $this->_db->where($selectData)->count();
    }

    /**
     * 功能：添加一条素材记录
     * @param array $data
     * @return mixed
     */
    public function add_one_matter($data = array()) {
        if(!$data || !isset($data))
            throw_exception('数据不存在！');
        return $this->_db->add($data);
    }
}