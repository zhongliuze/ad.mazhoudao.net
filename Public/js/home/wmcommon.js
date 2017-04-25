/**
 * Created by yuban on 2017/3/2.
 */

var modify_account = {
    check : function () {
        //1.1 获取html中用户输入内容
        var colum_name = $('input[name = "colum_name"]').val();
        var alipay_number = $('input[name = "alipay_number"]').val();

        var company_name = $('input[name = "company_name"]').val();
        var bank = $('input[name = "bank"]').val();
        var bank_account = $('input[name = "bank_account"]').val();
        var business = $('input[name = "business"]').val();
        var certificate = $('input[name = "certificate"]').val();



        //1.2 定义正则表达式
        /*var zusername = /^([\u4e00-\u9fa5a-zA-Z0-9]{3,12})$/; //用户名要求以字母开头，可以是数字或字母*/
        var zmobile=/^(1[34578]\d{9})$/;  //手机号码验证
        var zemail=/^(\w{1,}@\w{1,}\.\w{1,})$/; //邮箱验证

        //1.3 验证数据有效性

        if(colum_name && alipay_number) {
            if(!zmobile.test(alipay_number) && !zemail.test(alipay_number))
                return dialog.msg('请输入正确的支付宝账号！');
        }

        if(colum_name && alipay_number) {
            var data = {
                'alipay_name' : colum_name,
                'alipay_number' : alipay_number,
            };
        }
        if(company_name && bank && bank_account && business && certificate) {
            var data = {
                'company_name' : company_name,
                'bank' : bank,
                'bank_account' : bank_account,
                'business' : business,
                'certificate' : certificate,
            };
        }
        //1.4 数据封装

        //1.5 定义POST链接地址和跳转页面地址
        var postUrl = '/index.php?m=home&c=usermenu&a=modify_user_account';
        var jumpUrl = '/index.php?m=home&c=usermenu';

        //1.6 Ajax异步数据传输
        $.post(postUrl,data,function (result) {
            if(result.status == 0) {
                return dialog.msg(result.message);
            }else if(result.status == 1) {
                return dialog.msg_url(result.message,jumpUrl);
            }
        },'JSON');

    }
};