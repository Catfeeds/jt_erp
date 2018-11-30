<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '订单状态导入';
?>
<div class="orders-index box box-primary">
	<div class="box-body table-responsive">
		<div>
		    导入的excel格式：<br>
		    订单号,订单状态<br>
		    <font color="#ff0000"><?= $notice ?></font>
		</div>
		<?php
		echo Html::beginForm('import','post', ["enctype" => "multipart/form-data"]);

		echo Html::fileInput('orderData', null);
		echo "<br>";
		echo Html::submitButton('上传',['class'=>'btn btn-primary']);
		echo Html::endForm();
		?>
		<a href="/uploadTemplate/<?=urlencode('批量修改状态模板.xlsx')?>" target="_blank">下载模板</a>
	</div>
</div>