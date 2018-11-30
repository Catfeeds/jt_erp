<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Inventorys */

$this->title = 'Update Inventorys: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inventorys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="inventorys-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
