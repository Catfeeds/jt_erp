<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Back */

$this->title = '修改退货单: ' . $model->back;
$this->params['breadcrumbs'][] = ['label' => '退库单', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->back, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="back-update">

    <?= $this->render('_form', [
        'model' => $model,
        'backItems' => $backItems
    ]) ?>

</div>
