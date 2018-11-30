<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '库存批量更新';
?>
<div class="orders-index box box-primary">
	<div class="box-body table-responsive">
		<div>
		    导入的excel格式：<br>
            库存编号,SKU,数量,成本<br>
            <font color="#ff0000"><?= $notice ?></font>
		</div>
		<?php
		echo Html::beginForm('import-stocks','post', ["enctype" => "multipart/form-data"]);

		echo Html::fileInput('stocksData', null);
		echo "<br>";
		echo Html::submitButton('上传',['class'=>'btn btn-primary']);
		echo Html::endForm();
		?>
		<a href="/uploadTemplate/<?=urlencode('回款单模板.xlsx')?>" target="_blank">下载模板</a>
	</div>
</div>