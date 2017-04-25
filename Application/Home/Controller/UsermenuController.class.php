<?php
/**
 * FILE_NAME :  UsermenuController.class.php
 * 模块:home
 * 域名:union.yanqingkong.com
 *
 * 功能:用户菜单页面控制器
 *
 *
 * @copyright Copyright (c) 2017 – www.hongshu.com
 * @author liuzezhong@hongshu.com
 * @version 1.0 2017/2/28 14:06
 */

namespace Home\Controller;
use Think\Exception;


/**
 * usermenuController类
 *
 * 功能1：用户基本信息显示
 * 功能2：系统公告显示

 *
 * @author      liuzezhong <liuzezhong@hongshu.com>
 * @access      public
 * @abstract
 */
class UsermenuController extends CommonController{


    /**
     * 功能：从数据库中读取系统公告交给前台
     */
    public function sys_announcement() {
        try {
            //1.1 判断当前页码
            $data = array();
            $now_page = I('request.p',1,'intval');
            $page_size = I('request.pageSize',5,'intval');
            $page = $now_page ? $now_page : 1;
            //1.2 设置默认分页条数
            $pageSize = $page_size ? $page_size : 5;
            //1.3 数据库查询
            $announcement = D("Wmnotice")->get_all_announcement_limit($data,$page,$pageSize);
            $announcementCount = D("Wmnotice")->get_count_announcement();
            //1.4 实例化一个分页对象
            $res = new Wmpage($announcementCount,$pageSize);
            //1.5 调用show方法前台显示页码
            $pageRes = $res->show();
            //1.6 数据传递到前台模板
            $_SESSION['sys_announcement_url'] = $_SERVER['REQUEST_URI'];   //记录上页地址

            $this->assign(array(
                'count' => $announcementCount,
                'notices' => $announcement,
                'pageRes' => $pageRes,
            ));

        } catch (Exception $e) {
            $this->assign(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));        }
        $this->display();
    }

    /**
     * 功能：系统公告详情页面
     */
    public function sys_announcement_details() {
        //1.1 通过GET方法获取传递过来的公告ID
        $notice_id = I('get.notice',0,'intval');
        //1.2 数据校验
        if($notice_id == 0){
            $this->assign(array(
                'status' => 0,
                'message' => '公告ID不存在！',
            ));
        }
        //1.3 数据库操作
        try {
            //1.3.1 根据ID查询数据
            $notice_details = D('Wmnotice')->get_one_notice_by_id($notice_id);

            $notice_details['notice_content'] = htmlspecialchars_decode($notice_details['notice_content']);
            //1.3.2 验证数据有效性
            if(!$notice_details) {
                $this->assign(array(
                    'status' => 0,
                    'message' => '该公告内容丢失！',
                ));
            }
            //1.3.3 将数据传递给前台
            $this->assign('notices',$notice_details);

        } catch (Exception $e) {
            $this->assign(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
        $this->display();
    }

    /**
     * 功能：显示用户账户信息
     */
    public function index() {
        //1.1 获取session中用户ID
        $user_id = intval(I('session.adminUser')['user_id']);
        if(!$user_id){
            $this->assign(array(
                'status' => 0,
                'message' => '用户ID不存在！',
            ));
        }
        //1.2 根据用户ID在数据库中获取用户信息
        $user_info = D('Wmadmin')->get_one_user_by_id($user_id);
        //1.3 将数据传递到前台页面
        $this->assign('user',$user_info);
        //1.4 显示模板
        $this->display();
    }

    /**
     * 功能：显示用户基本信息
     */
    public function user_accounts(){
        //1.1 获取session中用户ID
        $user_id = intval(I('session.adminUser')['user_id']);
        if(!$user_id){
            $this->assign(array(
                'status' => 0,
                'message' => '用户ID不存在！',
            ));
        }
        //1.2 根据用户ID在数据库中获取用户信息
        $user_info = D('Wmadmin')->get_one_user_by_id($user_id);
        //1.3 将数据传递到前台页面
        $this->assign('user',$user_info);
        //1.4 显示模板
        $this->display();
    }

    public function user_account(){
        //1.1 获取session中用户ID
        $user_id = intval(I('session.adminUser')['user_id']);
        if(!$user_id){
            $this->assign(array(
                'status' => 0,
                'message' => '用户ID不存在！',
            ));
        }
        //1.2 根据用户ID在数据库中获取用户信息
        $user_info = D('Wmadmin')->get_one_user_by_id($user_id);
        //1.3 将数据传递到前台页面
        $this->assign('user',$user_info);
        //1.4 显示模板
        $this->display();
    }

    /**
     * 功能：修改用户账户信息
     */
    public function modify_user_account() {
        //1.1 数据过滤校验
        $user_id = intval(I('session.adminUser')['user_id']);
        $column_name = I('post.alipay_name','','trim,string');
        $alipay_number = I('post.alipay_number','','trim,string');
        $company_name = I('post.company_name','','trim,string');
        $bank = I('post.bank','','trim,string');
        $bank_account = I('post.bank_account','','trim,string');
        $business = I('post.business','','trim,string');
        $certificate = I('post.certificate','','trim,string');


        if($column_name && $alipay_number) {
            $data = array(
                'column_name' => $column_name,
                'alipay_number' => $alipay_number,
            );
        }
        if($company_name && $bank && $bank_account && $business && $certificate) {

            if(substr($business,0,40) == 'http://ad.mazhoudao.net/Uploads/runtime/')
                $business = copyUploadFile($business);
            if(substr($certificate,0,40) == 'http://ad.mazhoudao.net/Uploads/runtime/')
                $certificate = copyUploadFile($certificate);

            $del = delUploadFile();
            $data = array(
                'company_name' => $company_name,
                'bank' => $bank,
                'bank_account' => $bank_account,
                'business' => $business,
                'certificate' => $certificate,
                'account_status' => 0,
            );
        }

        //1.2 数据库操作
        try {
            //1.2.5 将用户提交信息写入数据库

            $data['update_time'] = time();
            $res = D('Wmadmin')->update_user_by_id($user_id,$data);
            //1.2.6 判断注册信息，返回更新结果
            if(!$res){
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '修改失败！',
                ));
            }
            $this->ajaxReturn(array(
                'status' => 1,
                'message' => '保存成功！',
            ));
        } catch (Exception $e) {
            //1.2.9 如有异常发生，返回异常信息
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
    }

    /**
     * 功能：用户业绩信息显示
     */
    public function user_achievement() {
        try {
            //1.1 将查询条件封装
            $data = array();
            //业绩类型为渠道业绩
            $data['ach_type'] = 1;
            if(I('get.begin_date','','trim,string')) {
                $data['begin_date'] = strtotime(I('get.begin_date','','trim,string'));
                $this->assign('begin_date',$data['begin_date']);
            }
            if(I('get.end_date','','trim,string')) {
                $data['end_date'] = strtotime(I('get.end_date','','trim,string'));
                $this->assign('end_date',$data['end_date']);
            }
            $data['user_id'] = I('session.adminUser')['user_id'];
            //1.2 判断当前页码
            $now_page = I('request.p',1,'intval');
            $page_size = I('request.pageSize',10,'intval');
            $page = $now_page ? $now_page : 1;
            //1.3 设置默认分页条数
            $pageSize = $page_size ? $page_size : 10;
            //1.4 数据库查询
            $achievement = D("Wmachievement")->get_all_achievement_limit($data,$page,$pageSize);
            $achievementCount = D("Wmachievement")->get_count_achievement($data);
            //1.5 实例化一个分页对象
            $res = new Wmpage($achievementCount,$pageSize);
            //1.6 调用show方法前台显示页码
            $pageRes = $res->show();
            //1.7 数据传递到前台模板
            $this->assign(array(
                'achievementCount' => $achievementCount,
                'achievements' => $achievement,
                'pageRes' => $pageRes,
            ));

        } catch (Exception $e) {
            $this->assign(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
        $this->display();
    }

    /**
     * 功能：用户业绩信息显示
     */
    public function user_underachievement() {
        try {
            //1.1 将查询条件封装
            $data = array();
            $data['ach_type'] = 2;
            if(I('get.begin_date','','trim,string')) {
                $data['begin_date'] = strtotime(I('get.begin_date','','trim,string'));
                $this->assign('begin_date',$data['begin_date']);
            }
            if(I('get.end_date','','trim,string')) {
                $data['end_date'] = strtotime(I('get.end_date','','trim,string'));
                $this->assign('end_date',$data['end_date']);
            }
            $data['user_id'] = I('session.adminUser')['user_id'];
            //1.2 判断当前页码
            $now_page = I('request.p',1,'intval');
            $page_size = I('request.pageSize',10,'intval');
            $page = $now_page ? $now_page : 1;
            //1.3 设置默认分页条数
            $pageSize = $page_size ? $page_size : 10;
            //1.4 数据库查询
            $achievement = D("Wmachievement")->get_all_achievement_limit($data,$page,$pageSize);
            $achievementCount = D("Wmachievement")->get_count_achievement($data);
            //1.5 实例化一个分页对象
            $res = new Wmpage($achievementCount,$pageSize);
            //1.6 调用show方法前台显示页码
            $pageRes = $res->show();
            //1.7 数据传递到前台模板
            $this->assign(array(
                'achievementCount' => $achievementCount,
                'achievements' => $achievement,
                'pageRes' => $pageRes,
            ));

        } catch (Exception $e) {
            $this->assign(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
        $this->display();
    }

    /**
     * 功能：用户提现记录信息显示
     */
    public function user_withdrawals() {
        try {
            //1.1 将查询条件封装
            $data = array();
            if(I('get.begin_date','','trim,string')) {
                $data['begin_date'] = strtotime(I('get.begin_date','','trim,string'));
                $this->assign('begin_date',$data['begin_date']);
            }
            if(I('get.end_date','','trim,string')) {
                $data['end_date'] = strtotime(I('get.end_date','','trim,string'));
                $this->assign('end_date',$data['end_date']);
            }
            $data['user_id'] = intval(I('session.adminUser')['user_id']);
            //1.2 判断当前页码
            $now_page = I('request.p',1,'intval');
            $page_size = I('request.pageSize',10,'intval');
            $page = $now_page ? $now_page : 1;
            //1.3 设置默认分页条数
            $pageSize = $page_size ? $page_size : 10;
            //1.4 数据库查询
            $canmoney = D("Wmachievement")->get_all_achievement_by_userid($data['user_id']);
            $withdrawals = D("Wmwithdrawals")->get_all_withdrawals_limit($data,$page,$pageSize);
            $withdrawalsCount = D("Wmwithdrawals")->get_count_withdrawals($data);
            $userinfo = D('Wmadmin')->get_one_user_by_id($data['user_id']);
            //1.5 实例化一个分页对象
            $res = new Wmpage($withdrawalsCount,$pageSize);
            //1.6 调用show方法前台显示页码
            $pageRes = $res->show();
            //1.7 数据处理
            $i = 1;   //列表序号
            foreach ($withdrawals as $k => $drawals) {
                $withdrawals[$k]['id'] = $i++;
            }

            $money = 0;  //可提现金额
            $duigong_money = 0;  //对公账户提现金额
            $money_detail = '';//提现详情
            $duigong_money_detail = '';//提现详情
            $checkDayStr = date('Y-m',time());
            $startTime = strtotime($checkDayStr.'-01');
            foreach ($canmoney as $k => $drawals) {
                if($drawals['pay_status'] == 0 && date('Y-m-d',$drawals['re_time']) != date('Y-m-d',time())) {
                    $money = $money + $drawals['divided_amount'];
                    if($drawals['acc_explain'])
                        $money_detail = $money_detail . $drawals['acc_explain'] .'+';
                }
                if($drawals['pay_status'] == 0 && $drawals['re_time'] < $startTime) {
                    $duigong_money = $duigong_money + $drawals['divided_amount'];
                    if($drawals['acc_explain'])
                        $duigong_money_detail = $duigong_money_detail . $drawals['acc_explain'] .'+';
                }
            }

            $money_detail = substr($money_detail,0,-1);   //删除最后一个+号
            $duigong_money_detail = substr($duigong_money_detail,0,-1);   //删除最后一个+号
            //预计时间
            $now_time = time();
            $estimate_time = $now_time + 86400*3 ;
            if(D('Wmwithdrawals')->isWeek($now_time,$estimate_time)) {
                $estimate_time = $estimate_time +86400*2;
            }

            //1.8 数据传递到前台模板
            $this->assign(array(
                'money' => $money,
                'duigong_money' => $duigong_money,
                'moneydetail' => $money_detail,
                'duigong_moneydetail' => $duigong_money_detail,
                'estimate_time' => $estimate_time,
                'withdrawals' => $withdrawals,
                'withdrawalsCount' => $withdrawalsCount,
                'pageRes' => $pageRes,
                'userinfo' => $userinfo,
                'gongsi' => C('GONGSI_XINXI'),
            ));

        } catch (Exception $e) {
            $this->assign(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
        $this->display();
    }

    /**
     * 功能进行提现操作
     */
    public function user_withdrawals_tx() {


        /**
         * 对公账户提现条件：
         * 1.银行账号为正确状态的对公账号可提现
         * 2.每月仅能申请一次
         * 3.如果对公提现，提现余额仅能显示上个月的余额，如果支付宝提现，可显示到今天的
         */
        //1.1 获取提现操作用户ID


        $type = I('post.type','','trim,string');
        $money_ach = I('post.money_ach','','trim,string');
        $fapiao = I('post.fapiao','','trim,string');
        $user_id = intval(I('session.adminUser',0,'intval')['user_id']);
        if($user_id == 0){
            $this->assign(array(
                'status' => 0,
                'message' => '用户ID不存在！',
            ));
        }

        //1.2 数据库操作写入提现记录
        try {
            //1.2.1 获取当前用户信息
            $userinfo = D('Wmadmin')->get_one_user_by_id($user_id);
            if($type == 'zhifubao') {
                // 判断是否绑定支付宝账户
                if(!$userinfo['alipay_number'] || !isset($userinfo['alipay_number'])) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'message' => '请先绑定支付宝账户信息！',
                    ));
                }
                // 判断是否在不允许提现时间内
                $checkDayStr = date('Y-m-d',time());
                $startTime = strtotime($checkDayStr."00:00".":00");
                $endTime = strtotime($checkDayStr."00:15".":00");
                if(time()>$startTime && time()<$endTime ){
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'message' => '此时间段内不允许提现！',
                    ));
                }
            } else if($type == 'duigongzhanghu') {
                // 判断是否绑定了对公提现账户
                if(!$userinfo['bank_account'] || !isset($userinfo['bank_account'])) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'message' => '请先绑定对公账户信息！',
                    ));
                }
                //判断对公提现账户状态
                if($userinfo['account_status'] == 0) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'message' => '您的对公账户未审核通过或冻结中！',
                    ));
                }
                //判断是否在允许提现时间内
                $checkDayStr = date('Y-m',time());
                $startTime = strtotime($checkDayStr . "-01" . "00:00" . ":00");
                $endTime = strtotime($checkDayStr . "-20" . "23:59" . ":59");
                if(time()<$startTime || time()>$endTime ){
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'message' => '对公账户提现时间为每月1至15日！',
                    ));
                }

                //判断当月是否已经申请过对公提现
                $if_duigong = array(
                    'user_id' => $user_id,
                    'tixian_type' => 1,
                    'pay_time' => array('BETWEEN',array($startTime,$endTime)),
                );
                $duigong_res = D('Wmwithdrawals')->get_duigong_tixian($if_duigong);
                if($duigong_res) {
                    $this->ajaxReturn(array(
                        'status' => 0,
                        'message' => '每月只可申请一次对公提现！',
                    ));
                }
            }


            //1.2.2 获取当前用户的业绩信息
            $canmoney = D("Wmachievement")->get_all_achievement_by_userid($user_id);
            //1.2.3 数据计算，得出可提现金额及提现详情
            $money = 0;  //可提现金额
            $money_detail = '';//提现详情

            $checkDayStr = date('Y-m',time());
            $startTime = strtotime($checkDayStr."-01");

            if($type == "zhifubao") {
                foreach ($canmoney as $k => $drawals) {
                    if($drawals['pay_status'] == 0 && date('Y-m-d',$drawals['re_time']) != date('Y-m-d',time())) {
                        $money = $money + $drawals['divided_amount'];
                        $money_detail = $money_detail . $drawals['acc_explain'] .'+';
                    }
                }
            } else if($type == "duigongzhanghu") {
                foreach ($canmoney as $k => $drawals) {
                    if($drawals['pay_status'] == 0 && $drawals['re_time'] < $startTime) {
                        $money = $money + $drawals['divided_amount'];
                        $money_detail = $money_detail . $drawals['acc_explain'] .'+';
                    }
                }
            }

            $money_detail = substr($money_detail,0,-1);   //删除最后一个+号

            if($money == 0){
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '没有足够的余额！',
                ));
            }

            if($money_ach != $money) {
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '提现金额有误，请稍后重试！',
                ));
            }

            if($type == 'zhifubao') {
                $data = array(
                    'user_id' => $user_id,   //用户ID
                    'tixian_type' => 0,     //提现方式：支付宝
                    'pay_money' => $money,           //提现金额
                    'column_name' => $userinfo['column_name'],   //支付宝名
                    'pay_account' => $userinfo['alipay_number'],   //支付宝账号
                    'pay_status' => 0,  //提现状态 0：正在提现  1：提现完成  -1:提现失败
                    'pay_time' => time(), //提现时间
                    'details_number' => '',   //提现详情 ，交易流水号 后台输入
                    'pay_reason' => $money_detail,//提现说明

                );
            } else if($type == 'duigongzhanghu') {
                $fapiao = copyUploadFile($fapiao);
                $del = delUploadFile();
                $data = array(
                    'user_id' => $user_id,   //用户ID
                    'tixian_type' => 1,   //提现方式：对公账户
                    'pay_money' => $money,           //提现金额
                    'company_name' => $userinfo['company_name'],   //公司名
                    'bank' => $userinfo['bank'],   //开户行
                    'bank_account' => $userinfo['bank_account'],   //银行卡号
                    'fapiao' => $fapiao,   //发票详情
                    'pay_status' => 0,  //提现状态 0：正在提现  1：提现完成  -1:提现失败
                    'pay_time' => time(), //提现时间
                    'details_number' => '',   //提现详情 ，交易流水号 后台输入
                    'pay_reason' => $money_detail,//提现说明

                );
            }

            //1.2.5 将数据写入数据库中
            $res = D('Wmwithdrawals')->add_one_withdrawals($data);
            //1.2.6 对结果进行判断
            if(!$res){
                $this->ajaxReturn(array(
                    'status' => 0,
                    'message' => '提现失败！',
                ));
            }
            //1.2.6 如果写入数据成功则将用户业绩表中的支付状态改为已提现
            if($res){
                //1.2.6.1 循环遍历
                if($type == 'zhifubao') {
                    foreach ($canmoney as $k => $drawals){
                        //1.2.6.2 如果状态为未提现
                        if($drawals['pay_status'] == 0) {
                            //1.2.6.2.1 将状态改为已提现
                            $data = array(
                                'pay_status' => 1,
                                'serial_number' => $res,
                            );
                            /*$canmoney[$k]['pay_status'] = 1;*/
                            //1.2.6.2.2 更新数据库
                            $res_ach = D("Wmachievement")->update_one_achievement_by_id($drawals['ach_id'],$data);
                            //1.2.6.2.3 判断结果
                            if(!$res_ach){
                                $this->ajaxReturn(array(
                                    'status' => 0,
                                    'message' => '提现失败！',
                                ));
                            }
                        }
                    }
                } else if($type == 'duigongzhanghu') {
                    foreach ($canmoney as $k => $drawals){
                        //1.2.6.2 如果状态为未提现
                        if($drawals['pay_status'] == 0 && $drawals['re_time'] < $startTime) {
                            //1.2.6.2.1 将状态改为已提现
                            $data = array(
                                'pay_status' => 1,
                                'serial_number' => $res,
                            );
                            /*$canmoney[$k]['pay_status'] = 1;*/
                            //1.2.6.2.2 更新数据库
                            $res_ach = D("Wmachievement")->update_one_achievement_by_id($drawals['ach_id'],$data);
                            //1.2.6.2.3 判断结果
                            if(!$res_ach){
                                $this->ajaxReturn(array(
                                    'status' => 0,
                                    'message' => '提现失败！',
                                ));
                            }
                        }
                    }
                }

                //1.2.6.2 返回结果
                $this->ajaxReturn(array(
                    'status' => 1,
                    'message' => '提现申请成功！',
                ));
            }

        } catch (Exception $e){
            //1.2.7 异常处理
            $this->assign(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
    }

    /**
     * 功能：用户下线信息展示
     */
    public function user_underline() {
        try {
            //1.1 将查询条件封装
            $data = array();
            if(I('get.begin_date','','trim,string')) {
                $data['begin_date'] = strtotime(I('get.begin_date','','trim,string'));
                $this->assign('begin_date',$data['begin_date']);
            }
            if(I('get.end_date','')) {
                $data['end_date'] = strtotime(I('get.end_date','','trim,string'));
                $this->assign('end_date',$data['end_date']);
            }
            if(I('get.username','','trim,string')) {
                $data['username'] = I('get.username','','trim,string');
                $this->assign('username',$data['username']);
            }
            $data['user_id'] = intval(I('session.adminUser')['user_id']);
            //1.2 判断当前页码
            $now_page = I('request.p',1,'intval');
            $page_size = I('request.pageSize',5,'intval');
            $page = $now_page ? $now_page : 1;
            //1.3 设置默认分页条数
            $pageSize = $page_size ? $page_size : 5;
            //1.4 数据库查询
            $user_under = D("Wmadmin")->get_all_underline_limit($data,$page,$pageSize);
            $underlineCount = D("Wmadmin")->get_count_underline($data);
            //1.5 实例化一个分页对象
            $res = new Wmpage($underlineCount,$pageSize);
            //1.6 调用show方法前台显示页码
            $pageRes = $res->show();
            //1.7 数据处理
            $i = 1;
            foreach ($user_under as $k => $underline) {
                $user_under[$k]['id'] = $i++;
            }
            //用户推广链接
            $under_url = 'http://'.$_SERVER['SERVER_NAME'].U('/home/register') . '?introducer='.$data['user_id'];
            //1.8 将数据传递给模板
            $this->assign(array(
                'user_under' => $user_under,
                'underlineCount' => $underlineCount,
                'pageRes' => $pageRes,
                'under_url' => $under_url,
            ));

        } catch (Exception $e) {
            $this->assign(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
        $this->display();
    }

    /**
     * 功能：素材下载页面显示
     */
    public function download_matter() {
        try {

            //1.1 将查询条件封装
            $data = array();
            if(I('get.begin_date','','trim,string')) {
                $data['begin_date'] = strtotime(I('get.begin_date','','trim,string'));
                $this->assign('begin_date',$data['begin_date']);
            }
            if(I('get.end_date','','trim,string')) {
                $data['end_date'] = strtotime(I('get.end_date','','trim,string'));
                $this->assign('end_date',$data['end_date']);
            }
            if(I('get.bookname','','trim,string')) {
                $data['bookname'] = I('get.bookname','','trim,string');
                $this->assign('bookname',$data['bookname']);
            }
            if(I('get.typeid',0,'intval')) {
                $data['type_id'] = I('get.typeid',0,'intval');
                $type_name = C('BOOK_TYPE')[$data['type_id']];
                $this->assign(array(
                    'type_id' => $data['type_id'],
                    'type_name' => $type_name,
                ));
            }

            //1.2 判断当前页码
            $now_page = I('request.p',1,'intval');
            $page_size = I('request.pageSize',5,'intval');
            $page = $now_page ? $now_page : 1;
            //1.3 设置默认分页条数
            $pageSize = $page_size ? $page_size : 5;
            //1.4 数据库查询
            $matter = D("Wmmatter")->get_all_matter_limit($data,$page,$pageSize);
            $matter_count = D("Wmmatter")->get_count_matter($data);
            $matter_type = C('BOOK_TYPE');
            $user_id = intval(I('session.adminUser')['user_id']);
            $user = D("Wmadmin")->get_one_user_by_id($user_id);
            $c_number = $user['c_number'];
            //1.5 实例化一个分页对象
            $res = new Wmpage($matter_count,$pageSize);
            //1.6 调用show方法前台显示页码
            $pageRes = $res->show();
            //1.7 数据处理

            foreach ($matter as $k => $item) {
                $matter[$k]['type_name'] = C('BOOK_TYPE')[$item['type_id']];
                $matter[$k]['after_url'] = $item['after_url'] . '#fromsid=' .$c_number;
                $status = D('Wmmatterstatus')->get_one_status_by_id($user_id,$item['matter_id']);
                if($status) {
                    $matter[$k]['status'] = $status['status'];
                }else
                    $matter[$k]['status'] = 0;
            }

            //1.8 将数据传递给模板
            $this->assign(array(
                'matters' => $matter,
                'matterCount' => $matter_count,
                'matterTypes' => $matter_type,
                'pageRes' => $pageRes,
            ));

        } catch (Exception $e) {
            $this->assign(array(
                'status' => 0,
                'message' => $e->getMessage(),
            ));
        }
        $this->display();
    }

    public function set_matter_status() {
        $matter_id= I('post.matter_id',0,'intval');
        $user_id = intval(I('session.adminUser')['user_id']);
        $status = I('post.checked',0,'intval');

        $data = array(
            'matter_id' => $matter_id,
            'user_id' => $user_id,
            'status' => $status,
        );

        //判断是否存在 user_id matter_id 的记录
        $res = D('Wmmatterstatus')->get_one_status_by_id($user_id,$matter_id);
        //如果存在 则修改状态，
        if($res) {
            $new = D('Wmmatterstatus')->update_one_status_by_data($res['status_id'],$data);
        }else if(!$res) {
            //如果不存在则新增状态
            $new = D('Wmmatterstatus')->add_one_status_by_data($data);
        }
    }

    /**
     * 功能：文件异步上传
     */
    public function ajaxUploadImage() {

        $upload = D("UploadImage");
        $res = $upload->imageUpload();

        if($res===false) {
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => '上传失败！'

            ));
        }else{
            $this->ajaxReturn(array(
                'status' => 1,
                'message' => '上传成功！',
                'data' => $res,
            ));

        }
    }

    public function upload_fapiao_again() {
        $serial_number = I('post.serial_number',0,'intval');
        $fapiao = I('post.fapiao','','trim,string');
        $data = array(
            'fapiao' => $fapiao,
            'fapiao_status' => 0,
        );
        $old_res = D('Wmwithdrawals')->get_one_withdrawal_by_serialnumber($serial_number);
        $old_fapiao = $old_res['fapiao'];
        $file = '../ad.mazhoudao.net/' . substr($old_fapiao,23);
        if (!unlink($file)) {
            $message = '原文件删除失败';
        }
        $res = D('Wmwithdrawals')->update_tixian_by_id($serial_number,$data);
        if(!$res) {
            $this->ajaxReturn(array(
                'status' => 0,
                'message' => '上传失败！',
            ));
        }
        $this->ajaxReturn(array(
            'status' => 1,
            'message' => '上传成功！',
        ));



    }
}