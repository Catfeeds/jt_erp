<?php
set_time_limit(0);
ini_set("memory_limit", "1024M");
// 引入自动加载文件
require 'autoload.php';
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
// 密钥
$accessKey = 'RHSetyjCwymGW7SJG9w1Xm9XRJZMqcr2mwB0PTX9';
$secretKey = 'WlQEXx_0YXQ4rRcr0gPUwG1u-bOJsmRw79WbaieD';

// 初始化鉴权对象
$auth = new Auth($accessKey, $secretKey);

$bucket = 'angeltmall';

// 生成上传token
$token = $auth->uploadToken($bucket);

// 空间管理对象 BucketManager
$bucketManager = new BucketManager($auth);

function getUrl($url, $save)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $file_content = curl_exec($ch);
    curl_close($ch);
    $down = fopen($save, 'w');
    fwrite($down, $file_content);
    fclose($down);
}

function list_file($bucketManager, $marker = '')
{
    static $num = 0;
    $num++;
    list($ret, $err) = $bucketManager->listFiles('angeltmall', '', $marker, '', '');
    if ($err !== null) {
        var_dump($err);exit;
    } else {
            $file = '/data/imgname/item_all';
        if (array_key_exists('marker', $ret)) {
	    foreach ($ret['items'] as $item) {
		//$time = date("Y-m-d H:i:s", substr($item['putTime'], 0, 10));
                file_put_contents($file, $item['key'].PHP_EOL, FILE_APPEND);
            }
            list_file($bucketManager, $ret['marker']);
        } else {
	    foreach ($ret['items'] as $item) {
                file_put_contents($file, $item['key'].PHP_EOL, FILE_APPEND);
            }
            echo 'done.';exit;
        }
    }
}
list_file($bucketManager, '');
