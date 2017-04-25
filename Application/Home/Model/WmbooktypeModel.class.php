<?php
/**
 * Created by PhpStorm.
 * User: yuban
 * Date: 2017/3/8
 * Time: 11:33
 */

namespace Home\Model;


use Think\Model;

class WmbooktypeModel extends Model{
    private $_db = '';

    /**
     * 功能：默认构造方法
     * WmwithdrawalsModel constructor.
     */
    public function __construct() {
        $this->_db = M('wms_booktype');
    }

    /**
     * 功能：获取分类标签内容
     * @return mixed
     */
    public function get_all_booktype() {
        return $this->_db->order('create_time desc,type_id desc')->select();
    }

    /**
     * 根据分类的ID号查询数据
     * @param $type_id
     * @return mixed
     */
    public function get_one_booktype_by_id($type_id) {
        if(!$type_id || !isset($type_id))
            throw_exception('该类型不存在！');
        return $this->_db->where('type_id = ' . $type_id)->find();
    }
}