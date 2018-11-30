<?php
header('Access-Control-Allow-Origin:*');

/**
 * 返回json格式数据.
 *
 * @author YXH
 * @date 2018/05/11
 */
function json_return($res)
{
    echo json_encode($res);
    exit;
}

/**
 * fdfs文件上传.
 *
 * @return fileId 存储服务器文件地址
 *
 * @author YXH
 * @date 2018/05/11
 **/
function fdfsUpload($tmpName, $fileType, $fileName, $format)
{
    $curlFile = new CurlFile($tmpName, $fileType, $fileName);
//file_put_contents("/usr/local/nginx/html/img.log", json_encode($curlFile).PHP_EOL, FILE_APPEND);
    //preg_match('/\.(\w+)?$/', $curlFile->getPostFilename(), $matchs);
    //$suffix = $matchs[1] ?? '';
    // 针对PHP上传的临时文件
    //if ($suffix == 'tmp') {
    //    $suffix = $format;
    //}
    // fdfs实例处理图片
    $fdfsObj = new FastDFS();
    $tracker = $fdfsObj->tracker_get_connection();
    $content = file_get_contents($curlFile->getFilename());
    $fileId = $fdfsObj->storage_upload_by_filebuff1($content, $format);
//file_put_contents("/usr/local/nginx/html/img.log", $fileId.PHP_EOL, FILE_APPEND);

    $fdfsObj->tracker_close_all_connections();

    return $fileId;
}

/**
 * 接收PHP客户端图片数据,保存到storage服务器.
 *
 * @param @field 上传字段 根据自己设置相对应
 * @param @format php上传的tmp文件时需要制定格式

 * @author YXH
 * @date 2018/05/11
 * @return json
 */
function uploadAttach($field, $format = 'jpg')
{
    $ret = [];
    $ret['status'] = 0;
    $ret['msg'] = '';

    // 判断是否存在文件流
    if (!$_FILES || isset($_FILES[$field]) == false) {
        $ret['status'] = 1;
        $ret['msg'] = 'ERROR: '.$field.' is not set';
        json_return($ret);
    }

    // 常规文件判断
    $file = $_FILES[$field];
    $tmpName = $file['tmp_name'];
    $count = is_array($tmpName) ? count($file['tmp_name']) : 1;

    if ($count == 1) {
        // 单文件上传
        if (!is_uploaded_file($tmpName)) {
            $ret['status'] = 2;
            $ret['msg'] = 'tmp_name is not file';
            json_return($ret);
        }
	$ret['path'] = fdfsUpload($tmpName, $file['type'], $file['name'], $format);
    } else {
        // 多文件上传
        for ($i = 0; $i < $count; $i++) {
            if (!is_uploaded_file($tmpName[$i])) {
                $ret['status'] = 2;
                $ret['msg'] = 'tmp_name is not file'.' NO.'.$i;
                json_return($ret);
            }

	    $ret['path'][] = fdfsUpload($tmpName[$i], $file['type'][$i], $file['name'][$i], $format);
        }
    }

    json_return($ret);
}

// 运行
$field = $_POST['file_field'] ?? 'fdfs_file';
$token = $_POST['token'] ?? '';
$time = $_POST['time'] ?? '';
$format = $_POST['format'] ?? 'jpg';

if ($token != md5('salt001'.$time)) {
    json_return(['status' => 4,'msg' => 'token error']);
} 

uploadAttach($field, $format);
