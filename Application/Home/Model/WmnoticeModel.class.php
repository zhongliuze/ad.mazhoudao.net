<?php
/**
 * FILE_NAME :  WmnoticeModel.class.php
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
 * WmnoticeModel类
 *
 * 功能1：新增一个公告信息
 * 功能2：获取所有的公告信息按时间降序排列
 * 功能3：根据ID号删除一条记录
 * 功能4：根据ID号更新一条数据
 *
 * @author      liuzezhong <liuzezhong@hongshu.com>
 * @access      public
 * @abstract
 */
class WmnoticeModel extends Model {
    private $_db = '';

    /**
     *Model constructor.
     *Model的默认构造方法
     */
    public function __construct() {
        $this->_db = M('wms_notice');
    }

    /**
     * 功能：获取所有的公告信息按时间降序排列
     * @return mixed
     */
    public function get_all_notice() {
        return $this->_db->order('notice_time desc')->select();
    }


    /**
     * 功能：新增一个公告信息
     * @param array $data
     * @return mixed
     */
    public function add_notice($data = array()) {
        if(!$data || !is_array($data)) {
            throw_exception('新增数据不存在！');
        }
        return $this->_db->add($data);
    }

    /**
     * 功能：根据ID号删除一条记录
     * @param int $id
     * @return mixed
     */
    public function del_one_notice_by_id($id = 0) {
        return $this->_db->where('notice_id = ' . $id)->delete();
    }

    /**
     * 功能：根据ID号更新一条数据
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update_one_notice_by_id($id = 0,$data = array()) {
        return $this->_db->where('notice_id = ' . $id)->save($data);
    }

    /**
     * 功能：获取公告并分页显示
     * @param array $data  查询条件
     * @param $page   当前页码
     * @param int $pageSize   每页条数
     * @return mixed
     */
    public function get_all_announcement_limit($data,$page,$pageSize = 10) {
        $selectData = array();
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['notice_time'] = array('BETWEEN',$data['sql_date']);   //查询表达式
        }

        if(isset($data['notice_title']))
            $selectData['notice_title'] = array('like','%'.$data['notice_title'].'%');
        //1.1 设置起始位置
        $offset = ($page - 1) * $pageSize;
        //1.2 查询数据按时间降序排列
        $list = $this->_db->where($selectData)->order('notice_time desc')->limit($offset,$pageSize)->select();
        //1.3 返回查询结果
        return $list;
    }

    /**
     * 功能：获取总记录数
     * @return mixed
     */
    public function get_count_announcement($data){

        $selectData = array();
        if(isset($data['begin_date']) && isset($data['end_date'])) {
            $data['sql_date'] = array($data['begin_date'],$data['end_date']);
            $selectData['notice_time'] = array('BETWEEN',$data['sql_date']);   //查询表达式
        }

        if(isset($data['notice_title']))
            $selectData['notice_title'] = array('like','%'.$data['notice_title'].'%');
        //1.1 返回查询结果
        return $this->_db->where($selectData)->count();
    }

    /**
     * 功能：根据公告ID查询公告信息
     * @param $notice_id
     */
    public function get_one_notice_by_id($notice_id) {
        if(!$notice_id || !isset($notice_id))
            throw_exception('公告ID不存在！');
        return $this->_db->where('notice_id = ' . $notice_id)->find();
    }
}