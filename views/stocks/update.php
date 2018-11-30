<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Stocks */

$this->title = 'Update Stocks: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="stocks-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
