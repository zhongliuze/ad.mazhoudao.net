/**
 * Created by yuban on 2017/3/7.
 */
/*
 var search_button = {
 check : function () {
 var begin_date = $('input[name = "begin_date"]').val();
 var end_date = $('input[name = "end_date"]').val();

 var data = {
 'begin_date' : begin_date,
 'end_date' : end_date,

 };

 //1.5 定义POST链接地址和跳转页面地址
 var postUrl = 'index.php/home/usermenu/user_achievement';
 var jumpUrl = 'index.php/home/usermenu/user_achievement';

 //1.6 Ajax异步数据传输
 $.post(postUrl,data,function (result) {
 if(result.status == 0) {
 return dialog.msg(result.message);
 }else if(result.status == 1) {
 return dialog.msg_url('',jumpUrl);
 }
 },'JSON');
 }
 };
 */

var Withdrawalsnow = {
    check : function () {
        var postUrl = '/index.php/home/usermenu/user_withdrawals_tx';
        var jumpUrl = '/index.php/home/usermenu/user_withdrawals';
        var money_ach = $('input[name = "money_ach"]').val();
        var type = $('input[name = "optionsRadios"]:checked').val();
        var fapiao = $('input[name = "fapiao"]').val();
        var data = {
            'fapiao' : fapiao,
            'type' : type,
            'money_ach' : money_ach,
        }
        if(type == 'duigongzhanghu') {
            if(!fapiao) {
                return dialog.msg('请上传增值税发票信息！');
            }
        }

        if(money_ach < 0 || money_ach == 0)
            return dialog.msg('没有足够的余额！');
        $.post(postUrl,data,function (result) {
            if(result.status == 0) {
                return dialog.msg(result.message);
            }else if(result.status == 1) {
                return dialog.msg_url(result.message,jumpUrl);
            }
        },'JSON');
    }
};


