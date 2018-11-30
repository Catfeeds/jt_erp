<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ReceiptAbnormal */

$this->title = '新建异常收货单';
$this->params['breadcrumbs'][] = ['label' => '异常收货单', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-abnormal-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
