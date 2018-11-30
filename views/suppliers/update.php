<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Suppliers */

$this->title = 'Update Suppliers: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="suppliers-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
