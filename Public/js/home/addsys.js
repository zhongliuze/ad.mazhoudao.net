/**
 * FILE_NAME :  login.js
 * 模块:home
 * 域名:ad.mazhoudao.net
 *
 * 功能:用户登陆，获取用户输入数据并校验，异步POST至后台服务器验证并获得登陆结果
 *
 *
 * @copyright Copyright (c) 2017 – www.hongshu.com
 * @author liuzezhong@hongshu.com
 * @version 1.0 2017/3/1 14:06
 */
var addsys = {
    check : function () {

        //1.1 获取html中用户输入内容
        var title = $('input[name = "title"]').val();
        var detail = $('textarea[name = "detail"]').val();

        //1.2 验证获取数据的有效性
        if(!title)
            return dialog.msg('标题不能为空！');
        if(!detail)
            return dialog.msg('公告详情不能为空！');

        //1.3 数据封装
        var data = {
            'title' : title,
            'detail' : detail,
        };

        //1.4 定义POST链接地址和跳转页面地址
        var postUrl = '/index.php?m=home&c=usermenu&a=input_sys_announcement_check';
        var jumpUrl = '/index.php?m=home&c=usermenu&a=input_sys_announcement';

        //1.5 Ajax异步数据传输
        $.post(postUrl,data,function (result) {
            if(result.status == 0) {
                return dialog.msg(result.message);
            }else if(result.status == 1) {
                return dialog.msg_url(result.message,jumpUrl);
            }
        },'JSON');
    }
};