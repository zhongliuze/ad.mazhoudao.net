/**
 * Created by liuzezhong on 2016/11/8.
 */

/**
 * 图片上传功能
 */
$(function() {
    $('#file_upload').uploadify({
        'swf'      : SCOPE.ajax_upload_swf,
        'uploader' : SCOPE.ajax_upload_image_url,
        'buttonText': '上传文件',
        'fileTypeDesc': 'Upload Files',
        'fileObjName' : 'file',

        //允许上传的文件后缀
        'fileTypeExts': '*.doc;*.docx;*.gif; *.jpg; *.png',
        'onUploadSuccess' : function(file,data,response) {
            // response true ,false
            if(response) {
                var obj = JSON.parse(data); //由JSON字符串转换为JSON对象

                $('#' + file.id).find('.data').html('上传完毕');
                var info = file.name + '上传成功！';

                $("#upload_org_code_img").html(info);
                $("#file_upload_image").attr('value',obj.data);
                $("#upload_org_code_img").show();
            }else{
                alert('上传失败!');
            }
        },
    });
});