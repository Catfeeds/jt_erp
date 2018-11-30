<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '广告导入';
?>
<div class="orders-index box box-primary">
	<div class="box-body table-responsive">
		<div>
		    导入的excel格式：<br>
		    站点ID,广告总额,广告费用日期<br>
		    <font color="#ff0000"><?= $notice ?></font>
		</div>
		<?php
		echo Html::beginForm('import','post', ["enctype" => "multipart/form-data"]);

		echo Html::fileInput('adData', null);
		echo "<br>";
		echo Html::submitButton('上传',['class'=>'btn btn-primary']);
		echo Html::endForm();
		?>
	</div>
</div>