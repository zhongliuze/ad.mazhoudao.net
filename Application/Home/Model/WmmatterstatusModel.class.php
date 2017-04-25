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
class WmmatterstatusModel extends Model {
    private $_db = '';

    /**
     * 功能：默认构造方法
     * WmwithdrawalsModel constructor.
     */
    public function __construct() {
        $this->_db = M('wms_matter_status');
    }

    public function get_one_status_by_id($user_id = 0 ,$matter_id = 0) {
        $data = array(
            'user_id' => $user_id,
            'matter_id' => $matter_id,
        );
        return $this->_db->where($data)->find();
    }

    public function update_one_status_by_data($status_id = 0, $data = array()) {
        return $this->_db->where('status_id = ' . $status_id)->save($data);
    }

    public function add_one_status_by_data($data = array()) {
        return $this->_db->add($data);
    }
}