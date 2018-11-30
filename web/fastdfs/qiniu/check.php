<?php
set_time_limit(0);
ini_set('max_execution_time', 0);

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

function check()
{
	$list = file_get_contents('/data/imgname/item_all');
	$arr = array_filter(explode(PHP_EOL, $list));
	foreach ($arr as $item) {
		$b = strpos($item, '.');
		$c = substr($item, $b+1);
		$path = '/data/cdn_img/'.$item;
		$xx = '/data/imgname/item_broken';
		if ($c == 'jpg') {
			if(@imagecreatefromjpeg($path) == false) {
				file_put_contents($xx, $item.PHP_EOL, FILE_APPEND);
			}
		} elseif($c == 'png') {
			if(@imagecreatefrompng($path) == false) {
				file_put_contents($xx, $item.PHP_EOL, FILE_APPEND);
			}
		} elseif($c == 'gif') {
			if(@imagecreatefromgif($path) == false) {
				file_put_contents($xx, $item.PHP_EOL, FILE_APPEND);
			}
		} 

	}
	echo "done";exit;
	}

check();
