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
 * @version 1.0 2017/2/28 14:06
 */

var Login = {
  check : function () {

      //1.1 获取html中用户输入内容
      var username = $('input[name = "username"]').val();
      var password = $('input[name = "password"]').val();
      var vercode = $('input[name = "vercode"]').val();
      //1.2 定义正则表达式
      var zusername = /^([\u4e00-\u9fa5a-zA-Z0-9]{3,12})$/;  //用户名要求以字母开头，可以是数字或字母
      var zpassword = /^.{6,20}$/;   //密码不低于6位不高于20位

      //1.3 验证获取数据的有效性
      if(!username)
          return dialog.msg('请输入用户名！');
      if(!password)
          return dialog.msg('请输入密码！');
      if(!vercode)
          return dialog.msg('请输入验证码！');
      if(!zusername.test(username))
          return dialog.msg('用户名不符合要求！');
      if(!zpassword.test(password))
          return dialog.msg('密码长度限制为6-12位字符');

      //1.4 数据封装
      var data = {
          'username' : username,
          'password' : password,
          'vercode' : vercode,
      };

      //1.5 定义POST链接地址和跳转页面地址
      var postUrl = '/index.php/home/login/verify_login';
      var jumpUrl = '/index.php/home/usermenu';

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

