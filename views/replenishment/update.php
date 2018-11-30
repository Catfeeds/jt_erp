<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Replenishment */

$this->title = 'Update Replenishment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Replenishments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="replenishment-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
