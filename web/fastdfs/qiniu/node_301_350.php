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

function save_img()
{
	static $start = 300;
	$start++;
	if ($start > 350) {
		echo '350 done';exit;
	} else {
		$list = file_get_contents('/data/imgname/item_'.$start);
		$arr = array_filter(explode(PHP_EOL, $list));
		foreach ($arr as $item) {
			if (!file_exists('/data/cdn_img/'.$item)) {
				try{
					getUrl('http://cdn.wiisoft.com/'.$item, '/data/cdn_img/'.$item);
				} catch( \Exception $e) {
					continue;
				}
			}
		}
		save_img();
	}

}

save_img();
