<?php
/**
 * Created by PhpStorm.
 * User: liuzezhong
 * Date: 2017/2/1
 * Time: 20:16
 */

/**
 * 功能：Ajax返回的json格式的方法
 * @param $status
 * @param $message
 * @param array $data
 */
function show($status,$message,$data = array() ) {
    $result = array(
        'status' => $status,
        'message' => $message,
        'data' => $data,
    );
    exit(json_encode($result));
}

/**
 * 功能：对密码进行MD5加密
 * @param $password
 * @return string
 */
function getMD5Password($password) {
    return md5($password .C('MD5_PRE'));
}

/**
 * 功能：获取客户端IP地址
 * @return string
 */
function getIP(){
    $realip = '';
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        }
    }
    return $realip;
}

/**
 * 发送POST请求
 */
function curlData($url, $data, $cookie = '') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if (stripos($url, 'https://') !== false) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $header = array();
    if ($cookie) {
        if (is_array($cookie)) {
            $tmp = $cookie;
            $cookie = '';
            foreach ($tmp as $k => $v) {
                $cookie .= $k . '=' . $v . '; ';
            }
        }
        $header[] = 'Cookie: ' . $cookie;
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);

    curl_close($ch);
    return $response;
}

/**
 * 删除目录及目录下所有文件或删除指定文件
 * @param str $path   待删除目录路径
 * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
 * @return bool 返回删除状态
 */

function delDirAndFile($path, $delDir = FALSE) {
    $handle = opendir($path);
    if ($handle) {
        while (false !== ( $item = readdir($handle) )) {
            if ($item != "." && $item != "..")
                is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
        }
        closedir($handle);
        if ($delDir)
            return rmdir($path);
    }else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return FALSE;
        }
    }
}

function copyUploadFile($fapiao) {
    $fapiao = substr($fapiao,46); // '2017/04/24/58fdb8d508678.jpg'
    $file = 'Uploads/runtime/files/' . $fapiao; //'Uploads/runtime/files/2017/04/24/58fdb8d508678.jpg'
    $dir = './Uploads/' . substr($fapiao,0,11);  //'./Uploads/2017/04/24/'
    if(!file_exists($dir))
        mkdir($dir,0,true);
    $newFile = 'Uploads/' . $fapiao;
    copy($file,$newFile);
    //$sta = delDirAndFile('./Uploads/runtime/files/',true);
    $fapiao = 'http://ad.mazhoudao.net/Uploads/' . $fapiao;
    return $fapiao;
}

function delUploadFile() {
    $sta = delDirAndFile('./Uploads/runtime/files/',true);
    return $sta;
}
