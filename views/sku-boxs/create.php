<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SkuBoxs */

$this->title = '添加SKU对应';
$this->params['breadcrumbs'][] = ['label' => 'SKU对应表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sku-boxs-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
