<?php
/**
 * FILE_NAME :  UploadImageModel.class.php
 * 模块:Wmbackstage
 * 域名:union.yanqingkong.com
 *
 * 功能:文件上传操作模型
 *
 *
 * @copyright Copyright (c) 2017 – www.hongshu.com
 * @author liuzezhong@hongshu.com
 * @version 1.0 2017/3/14 10:14
 */

namespace Home\Model;
use Think\Model;

class UploadImageModel extends Model {
    private $_uploadObj = '';
    const UPLOAD = 'Uploads';    //定义上传文件夹

    public function __construct() {
        $this->_uploadObj = new  \Think\Upload();
        $this->_uploadObj->rootPath = './Uploads/runtime/';
        $this->_uploadObj->subName = 'files/' . date(Y) . '/' . date(m) .'/' . date(d);
    }

    public function imageUpload() {
        $res = $this->_uploadObj->upload();
        if($res) {
            return 'http://ad.mazhoudao.net/Uploads/runtime/' . $res['file']['savepath'] . $res['file']['savename'];
        }else{
            return false;
        }
    }
}
