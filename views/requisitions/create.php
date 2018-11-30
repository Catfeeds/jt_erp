<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Requisitions */

$this->title = '新增调拨单';
$this->params['breadcrumbs'][] = ['label' => 'Requisitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requisitions-create">

    <?= $this->render('_form', [
    'model' => $model,
    'items_list' => $items_list,
    'stock_list' => $stock_list
    ]) ?>

</div>
