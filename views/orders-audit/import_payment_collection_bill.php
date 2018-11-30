<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '回款单导入';
?>
<div class="orders-index box box-primary">
	<div class="box-body table-responsive">
		<div>
		    导入的excel格式：<br>
            订单号,运单号,回款金额,COD手续费,运费,其它费用<br>
            <font color="#ff0000"><?= $notice ?></font>
		</div>
		<?php
		echo Html::beginForm('import-payment-collection-bill','post', ["enctype" => "multipart/form-data"]);

		echo Html::fileInput('orderData', null);
		echo "<br>";
		echo Html::submitButton('上传',['class'=>'btn btn-primary']);
		echo Html::endForm();
		?>
		<a href="/uploadTemplate/<?=urlencode('回款单模板.xlsx')?>" target="_blank">下载模板</a>
	</div>
</div>