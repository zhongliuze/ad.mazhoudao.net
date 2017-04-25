/**
 * FILE_NAME :  register.js
 * 模块:home
 * 域名:work2.hongshutest.com
 *
 * 功能:用户注册，获取前台数据并校验，异步POST至后台服务器
 *
 *
 * @copyright Copyright (c) 2017 – www.hongshu.com
 * @author liuzezhong@hongshu.com
 * @version 1.0 2017/2/28 14:06
 */

var Register = {
  check : function () {

      //1.1 获取html中用户输入内容
      var username = $('input[name = "username"]').val();
      var password = $('input[name = "password"]').val();
      var public_name = $('input[name = "public_name"]').val();
      var alipay_name = $('input[name = "alipay_name"]').val();
      var alipay_number = $('input[name = "alipay_number"]').val();
      var introducer_id = $('input[name = "introducer_id"]').val();
      var vercode = $('input[name = "vercode"]').val();
      var checked = $("input[name='checkbox']:checked").val();

      //1.2 定义正则表达式
      var zusername = /^([\u4e00-\u9fa5a-zA-Z0-9]{3,12})$/; //用户名要求以字母开头，可以是数字或字母
      var zpassword = /^.{6,20}$/;  //密码不低于6位不高于20位
      /*var zmobile=/^(1[34578]\d{9})$/;  //手机号码验证
      var zemail=/^(\w{1,}@\w{1,}\.\w{1,})$/; //邮箱验证*/

      //1.3 验证数据有效性
      if(!username)
          return dialog.msg('请输入用户名！');
      if(!password)
          return dialog.msg('请输入密码！');
      if(!public_name)
          return dialog.msg('请输入公众号！');
      if(!vercode)
          return dialog.msg('请输入验证码！');
      if(checked != 'checked')
          return dialog.msg('请仔细阅读并同意《言情控推广网盟协议》！');
      /*if(!alipay_name)
          return dialog.msg('请输入支付宝账户名！');
      if(!alipay_number)
          return dialog.msg('请输入支付宝账号！');*/
      if(!zusername.test(username))
          return dialog.msg('用户名不符合要求！');
      if(!zpassword.test(password))
          return dialog.msg('密码长度限制为6-12位字符');
      /*if(!zmobile.test(alipay_number) && !zemail.test(alipay_number))
          return dialog.msg('请输入正确的支付宝账号！');*/

      if(!introducer_id)
          introducer_id = 0;

      //1.4 数据封装
      var data = {
          'username' : username,
          'password' : password,
          'public_name' : public_name,
          /*'alipay_name' : alipay_name,
          'alipay_number' : alipay_number,*/
          'introducer_id' : introducer_id,
          'vercode' : vercode,
      };

      //1.5 定义POST链接地址和跳转页面地址
      var postUrl = '/index.php/home/register/user_register';
      var jumpUrl = '/index.php/home/login';

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