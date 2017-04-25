/**
 * Created by yuban on 2017/3/6.
 */


$('.useradmin_table #useradmin-status').on('click',function () {      //切换状态
    var user_id = $(this).attr('attr-id');
    var status = $(this).attr('attr-status');

    if(status == 0) {
        user_status = 1;
    }
    if(status == 1) {
        user_status = 0;
    }
    postData = {
        'user_id' : user_id,
        'user_status' : user_status,
    };
    url = "/union.yanqingkong.com/index.php?m=wmbackstage&c=index&a=user_management_changetype";

    layer.open({
        type : 0,
        title : '请再次确定',
        btn : ['是','否'],
        icon : 3,
        closeBtn : 2,
        content : "是否切换状态",
        scrollbar : true,
        yes : function () {
            //执行跳转
            tochange(url,postData);   //抛送ajax请求
        }

    });

});


$('#add_material').on('click',function () {
    var book_id = $('input[name = "bookid"]').val();
    var book_name = $('input[name = "bookname"]').val();
    var book_type = $('option:selected').val();
    var book_detail =$('input[name = "bookdetail"]').val();
    var afterurl = $('input[name = "afterurl"]').val();
    var file_up = $('input[name = "file_up"]').val();


    if(!book_id)
        return dialog.msg('请输入书号！');
    if(!book_name)
        return dialog.msg('请输入书名！');
    if(!book_type)
        return dialog.msg('请选择类型！');
    if(!book_detail)
        return dialog.msg('请输入素材详情！');
    if(!afterurl)
        return dialog.msg('请输入后续阅读地址！');

    var postData = {
        'book_id' : book_id,
        'book_name' : book_name,
        'book_type' : book_type,
        'book_detail' : book_detail,
        'afterurl' : afterurl,
        'file_upload' : file_up,
    };

    //1.5 定义POST链接地址和跳转页面地址
    var postUrl = '/union.yanqingkong.com/index.php/wmbackstage/index/upload_material_check';

    $.post(postUrl,postData,function (result) {
        if(result.status == 0) {
            return dialog.error(result.message);
        }
        if(result.status == 1) {
            return dialog.success(result.message,'');
        }
    },'JSON');

});

$('#upload_bulletin').on('click',function () {
    //1.1 获取html中用户输入内容
    var title = $('input[name = "title"]').val();
    var detail = $('textarea[name = "detail"]').val();

    //1.2 验证获取数据的有效性
    if(!title)
        return dialog.msg('标题不能为空！');
    if(!detail)
        return dialog.msg('公告详情不能为空！');

    //1.3 数据封装
    var postData = {
        'title' : title,
        'detail' : detail,
    };

    //1.4 定义POST链接地址和跳转页面地址
    var postUrl = '/union.yanqingkong.com/index.php/wmbackstage/index/upload_bulletin_check';
    var jumpUrl = '';

    //1.5 Ajax异步数据传输
    $.post(postUrl,postData,function (result) {
        if(result.status == 0) {
            return dialog.error(result.message);
        }
        if(result.status == 1) {
            return dialog.success(result.message,'');
        }
    },'JSON');
});

$('#set_pay_success').on('click',function () {
    push = {};
    postData = {};
    $("input[name='status_checkbox']:checked").each(function  (i) {
        push[i] = $(this).val();
    });

    postData['push'] = push;
    postData['type'] = 1;
    console.log(push);

    url = "/union.yanqingkong.com/index.php?m=wmbackstage&c=index&a=set_paymentdata_check";

    layer.open({
        type : 0,
        title : '请再次确定',
        btn : ['是','否'],
        icon : 3,
        closeBtn : 2,
        content : "是否设为【已提现】！",
        scrollbar : true,
        yes : function () {
            //执行跳转
            tochange(url,postData);   //抛送ajax请求
        }

    });

});

$('#set_pay_failed').on('click',function () {
    push = {};
    postData = {};
    $("input[name='status_checkbox']:checked").each(function  (i) {
        push[i] = $(this).val();
    });
    if(push.length === 0)
        return dialog.error('至少勾选一条提现记录！');
    postData['push'] = push;
    postData['type'] = -1;


    url = "/union.yanqingkong.com/index.php?m=wmbackstage&c=index&a=set_paymentdata_check";

    layer.open({
        type : 0,
        title : '请再次确定',
        btn : ['是','否'],
        icon : 3,
        closeBtn : 2,
        content : "是否设为【提现失败】！",
        scrollbar : true,
        yes : function () {
            //执行跳转
            tochange(url,postData);   //抛送ajax请求
        }

    });

});


function tochange($url,$postData) {
    $.post(url,postData,function (result) {
        if(result.status == 0) {
            return dialog.error(result.message);
        }
        if(result.status == 1) {
            return dialog.success(result.message,'');
        }
    },'JSON');
}

/**
 * 功能：checkbox全选或全不选
 */
$("#check_all").click(function(){

    var test = $("input[name='status_checkbox']:checked").val();
    if(!test) {
        $("input[name='status_checkbox']").attr("checked","true");

    }else if(test) {
        $("input[name='status_checkbox']").removeAttr("checked");
    }



});

$("#save_paymentdata").click(function () {
    postData = {};

    $('.add_pay_form').each(function (i) {
        postData[i] = $(this).serializeArray();
    });

    url = "/union.yanqingkong.com/index.php?m=wmbackstage&c=index&a=entry_paymentdata_check";
    layer.open({
        type : 0,
        title : '请再次确定',
        btn : ['是','否'],
        icon : 3,
        closeBtn : 2,
        content : "是否保存记录！",
        scrollbar : true,
        yes : function () {
            //执行跳转
            tochange(url,postData);   //抛送ajax请求
        }

    });

});

$('#textarea_pay_button').click(function () {
    var text_data = $('textarea[name = "textarea_pay"]').val();
    var postData = {
        'text_data' : text_data,
    };

    var url = '/union.yanqingkong.com/index.php?m=wmbackstage&c=index&a=set_paymentdata';
    $.post(url,postData,function (result) {
        if(result.status == 0) {
            return dialog.error(result.message);
        }
        if(result.status == 1) {
            return dialog.success(result.message,'');
        }
    },'JSON');

});
