/**
 * 图片上传功能
 */
$(function() {
    $('#file_upload').uploadify({
        'fileSizeLimit' : '2MB',
        'swf'      : '/Public/plugins/uploadify/uploadify.swf',
        'uploader' : '/index.php?m=home&c=usermenu&a=ajaxUploadImage',
        'buttonText': '上传营业执照扫描件',
        'fileTypeDesc': 'Image Files',
        'fileObjName' : 'file',

        //允许上传的文件后缀

        'fileTypeExts': '*.gif; *.jpg; *.png',
        'onUploadSuccess' : function(file,data,response) {
            // response true ,false
            if(response) {
                var obj = JSON.parse(data); //由JSON字符串转换为JSON对象

              
                $('#' + file.id).find('.data').html(' 上传完毕');

                $("#upload_org_code_img").attr("src",obj.data);
                $("#file_upload_image").attr('value',obj.data);
                $("#upload_org_code_img_hide").hide();
                $("#upload_org_code_img").show();

            }else{
                alert('上传失败');
            }
        },
    });

    $('#file_upload2').uploadify({
        'fileSizeLimit' : '2MB',
        'swf'      : '/Public/plugins/uploadify/uploadify.swf',
        'uploader' : '/index.php?m=home&c=usermenu&a=ajaxUploadImage',
        'buttonText': '上传开户许可证扫描件',
        'fileTypeDesc': 'Image Files',
        'fileObjName' : 'file',

        //允许上传的文件后缀
        'fileTypeExts': '*.gif; *.jpg; *.png',
        'onUploadSuccess' : function(file,data,response) {
            // response true ,false
            if(response) {
                var obj = JSON.parse(data); //由JSON字符串转换为JSON对象

              
                $('#' + file.id).find('.data').html(' 上传完毕');

                $("#upload_org_code_img2").attr("src",obj.data);
                $("#file_upload_image2").attr('value',obj.data);
                $("#upload_org_code_img_hide2").hide();
                $("#upload_org_code_img2").show();

            }else{
                alert('上传失败');
            }
        },
    });

    $('#file_upload3').uploadify({
        'fileSizeLimit' : '2MB',
        'swf'      : '/Public/plugins/uploadify/uploadify.swf',
        'uploader' : '/index.php?m=home&c=usermenu&a=ajaxUploadImage',
        'buttonText': '上传增值税发票照片',
        'fileTypeDesc': 'Image Files',
        'fileObjName' : 'file',

        //允许上传的文件后缀
        'fileTypeExts': '*.gif; *.jpg; *.png',
        'onUploadSuccess' : function(file,data,response) {
            // response true ,false
            if(response) {
                var obj = JSON.parse(data); //由JSON字符串转换为JSON对象

                $('#' + file.id).find('.data').html(' 上传完毕');

                $("#upload_org_code_img3").attr("src",obj.data);
                $("#file_upload_image3").attr('value',obj.data);
                $("#upload_org_code_img3").show();
            }else{
                alert('上传失败');
            }
        },
    });

    $('#file_upload4').uploadify({
        'fileSizeLimit' : '2MB',
        'swf'      : '/Public/plugins/uploadify/uploadify.swf',
        'uploader' : '/index.php?m=home&c=usermenu&a=ajaxUploadImage',
        'buttonText': '重新选择增值税发票照片',
        'fileTypeDesc': 'Image Files',
        'fileObjName' : 'file',

        //允许上传的文件后缀
        'fileTypeExts': '*.gif; *.jpg; *.png',
        'onUploadSuccess' : function(file,data,response) {
            // response true ,false
            if(response) {
                var obj = JSON.parse(data); //由JSON字符串转换为JSON对象

                $('#' + file.id).find('.data').html(' 上传完毕');

                $("#upload_org_code_img4").attr("src",obj.data);
                $("#file_upload_image4").attr('value',obj.data);
                $("#upload_org_code_img4").show();
            }else{
                alert('上传失败');
            }
        },
    });
});





