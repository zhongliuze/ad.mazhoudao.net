-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-04-25 04:47:55
-- 服务器版本： 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wis_wms`
--

-- --------------------------------------------------------

--
-- 表的结构 `wis_wms_achievement`
--

CREATE TABLE `wis_wms_achievement` (
  `ach_id` int(11) NOT NULL COMMENT '业绩ID号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID号',
  `register` int(11) NOT NULL DEFAULT '0' COMMENT '渠道用户注册数',
  `recharge` int(11) NOT NULL DEFAULT '0' COMMENT '渠道用户订单数',
  `recharge_s` int(11) NOT NULL DEFAULT '0' COMMENT '渠道用户成功订单数',
  `commission` float(8,2) NOT NULL DEFAULT '0.00' COMMENT '渠道用户成功订单金额',
  `serial_number` int(11) NOT NULL DEFAULT '0' COMMENT '提现流水号',
  `underline_number` int(11) DEFAULT NULL COMMENT '发展下线数',
  `underline_money` float(8,2) DEFAULT NULL COMMENT '发展下线总收益',
  `divided_amount` float(8,2) DEFAULT NULL COMMENT '分成佣金',
  `acc_explain` varchar(200) DEFAULT NULL COMMENT '提现说明',
  `pay_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '提现状态   0:未提现  1：已提现  -1：提现失败  2：正在提现',
  `ach_type` tinyint(4) DEFAULT '1' COMMENT '业绩类型：1为渠道业绩，2为下线业绩',
  `re_time` int(11) DEFAULT '0' COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wis_wms_booktype`
--

CREATE TABLE `wis_wms_booktype` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wis_wms_matter`
--

CREATE TABLE `wis_wms_matter` (
  `matter_id` int(11) NOT NULL COMMENT '素材ID号',
  `matter_detail` text COMMENT '素材详情',
  `catename` varchar(100) DEFAULT NULL COMMENT '书名',
  `bid` int(11) DEFAULT NULL COMMENT '书号',
  `type_id` int(11) NOT NULL COMMENT '素材类型ID号',
  `upload_time` int(11) DEFAULT NULL COMMENT '素材上传时间',
  `upload_file` varchar(500) DEFAULT NULL COMMENT '素材附件文件路径',
  `after_url` varchar(200) NOT NULL COMMENT '素材后续阅读地址'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wis_wms_matter_status`
--

CREATE TABLE `wis_wms_matter_status` (
  `status_id` int(11) NOT NULL COMMENT '下载状态id',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `matter_id` int(11) NOT NULL COMMENT '素材ID',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '素材使用状态，默认为使用，1为已使用，0为未使用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wis_wms_notice`
--

CREATE TABLE `wis_wms_notice` (
  `notice_id` int(11) NOT NULL COMMENT '公告ID号',
  `notice_title` varchar(200) DEFAULT NULL COMMENT '公告标题',
  `notice_content` varchar(1000) DEFAULT NULL COMMENT '公告内容',
  `notice_time` int(11) DEFAULT NULL COMMENT '发布时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wis_wms_user`
--

CREATE TABLE `wis_wms_user` (
  `user_id` int(11) NOT NULL COMMENT '用户ID号',
  `username` varchar(50) DEFAULT NULL COMMENT '用户名',
  `password` varchar(50) DEFAULT NULL COMMENT '密码',
  `real_name` varchar(20) NOT NULL COMMENT '真实姓名',
  `phone_number` varchar(20) NOT NULL COMMENT '手机号码',
  `public_name` varchar(200) DEFAULT NULL COMMENT '公众号',
  `c_number` int(11) DEFAULT NULL COMMENT '渠道号',
  `user_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户状态',
  `column_name` varchar(200) DEFAULT NULL COMMENT '支付宝实名',
  `alipay_number` varchar(200) DEFAULT NULL COMMENT '支付宝账号',
  `company_name` varchar(200) DEFAULT NULL COMMENT '单位名称',
  `bank` varchar(200) NOT NULL COMMENT '开户行',
  `bank_account` varchar(50) NOT NULL COMMENT '银行账号',
  `account_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '对公账号状态：0冻结，1可提现',
  `business` varchar(500) NOT NULL COMMENT '营业执照',
  `certificate` varchar(500) NOT NULL COMMENT '银行开户许可证',
  `introducer_id` int(11) DEFAULT NULL COMMENT '上线用户ID',
  `underline_count` int(11) NOT NULL DEFAULT '0' COMMENT '下线用户数',
  `proportion` float NOT NULL COMMENT '分成比例',
  `user_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '用户类型：0普通用户，1超级用户',
  `register_time` int(11) NOT NULL COMMENT '注册时间',
  `update_time` int(11) NOT NULL COMMENT '最后修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `wis_wms_withdrawals`
--

CREATE TABLE `wis_wms_withdrawals` (
  `serial_number` int(11) NOT NULL COMMENT '交易流水号',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID号',
  `pay_money` float(8,2) DEFAULT NULL COMMENT '提现金额',
  `tixian_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '提现方式：0支付宝提现，1对公账户提现,默认为0',
  `column_name` varchar(200) NOT NULL COMMENT '支付宝名',
  `pay_account` varchar(200) DEFAULT NULL COMMENT '支付宝账号',
  `company_name` varchar(200) NOT NULL COMMENT '公司名称',
  `bank` varchar(200) NOT NULL COMMENT '开户行',
  `bank_account` varchar(200) NOT NULL COMMENT '银行账号',
  `fapiao` varchar(500) NOT NULL COMMENT '增值税发票截图',
  `fapiao_status` tinyint(11) NOT NULL COMMENT '发票状态：0未审核  1：通过  -1 无效',
  `huidan` varchar(250) NOT NULL COMMENT '汇款回单',
  `pay_time` int(11) DEFAULT '0' COMMENT '提现时间',
  `arrival_time` int(11) DEFAULT '0' COMMENT '预计到账时间',
  `pay_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '提现状态   0:未提现  1：已提现  -1：提现失败  2：正在提现',
  `details_number` varchar(200) NOT NULL COMMENT '交易详情（交易单号）',
  `pay_reason` text COMMENT '提现说明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wis_wms_achievement`
--
ALTER TABLE `wis_wms_achievement`
  ADD PRIMARY KEY (`ach_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ach_id` (`ach_id`,`user_id`,`serial_number`,`pay_status`,`re_time`);

--
-- Indexes for table `wis_wms_booktype`
--
ALTER TABLE `wis_wms_booktype`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `wis_wms_matter`
--
ALTER TABLE `wis_wms_matter`
  ADD PRIMARY KEY (`matter_id`),
  ADD KEY `matter_id` (`matter_id`,`bid`,`type_id`);

--
-- Indexes for table `wis_wms_matter_status`
--
ALTER TABLE `wis_wms_matter_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `wis_wms_notice`
--
ALTER TABLE `wis_wms_notice`
  ADD PRIMARY KEY (`notice_id`),
  ADD KEY `notice_id` (`notice_id`,`notice_time`);

--
-- Indexes for table `wis_wms_user`
--
ALTER TABLE `wis_wms_user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `username` (`username`,`c_number`,`user_status`,`introducer_id`),
  ADD KEY `underline_count` (`underline_count`);

--
-- Indexes for table `wis_wms_withdrawals`
--
ALTER TABLE `wis_wms_withdrawals`
  ADD PRIMARY KEY (`serial_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `serial_number` (`serial_number`,`user_id`,`pay_status`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `wis_wms_achievement`
--
ALTER TABLE `wis_wms_achievement`
  MODIFY `ach_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '业绩ID号', AUTO_INCREMENT=42;
--
-- 使用表AUTO_INCREMENT `wis_wms_booktype`
--
ALTER TABLE `wis_wms_booktype`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `wis_wms_matter`
--
ALTER TABLE `wis_wms_matter`
  MODIFY `matter_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '素材ID号', AUTO_INCREMENT=17;
--
-- 使用表AUTO_INCREMENT `wis_wms_matter_status`
--
ALTER TABLE `wis_wms_matter_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '下载状态id', AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `wis_wms_notice`
--
ALTER TABLE `wis_wms_notice`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '公告ID号', AUTO_INCREMENT=22;
--
-- 使用表AUTO_INCREMENT `wis_wms_user`
--
ALTER TABLE `wis_wms_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID号', AUTO_INCREMENT=44;
--
-- 使用表AUTO_INCREMENT `wis_wms_withdrawals`
--
ALTER TABLE `wis_wms_withdrawals`
  MODIFY `serial_number` int(11) NOT NULL AUTO_INCREMENT COMMENT '交易流水号', AUTO_INCREMENT=60;
--
-- 限制导出的表
--

--
-- 限制表 `wis_wms_achievement`
--
ALTER TABLE `wis_wms_achievement`
  ADD CONSTRAINT `wis_wms_achievement_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `wis_wms_user` (`user_id`);

--
-- 限制表 `wis_wms_withdrawals`
--
ALTER TABLE `wis_wms_withdrawals`
  ADD CONSTRAINT `wis_wms_withdrawals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `wis_wms_user` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
